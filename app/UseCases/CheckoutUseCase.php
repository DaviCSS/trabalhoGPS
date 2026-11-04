<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\PedidoModel;
use App\Models\ProdutoModel;
use App\Models\ItemPedidoModel;
use App\Models\PagamentoModel;
use App\Models\ArtesaoModel;
use App\Services\PixService;
use CodeIgniter\Database\BaseConnection;

/**
 * UC06 — Realizar Checkout
 * Usa id_cliente, id_artesao e id_endereco conforme o novo schema.
 * Após criar o pedido, delega a transação financeira ao UC08.
 */
class CheckoutUseCase
{
    public function __construct(
        private readonly PedidoModel    $pedidoModel,
        private readonly ProdutoModel   $produtoModel,
        private readonly ItemPedidoModel $itemPedidoModel,
        private readonly BaseConnection  $db,
        private readonly PagamentoModel  $pagamentoModel = new PagamentoModel()
    ) {}

    /**
     * @param int    $id_cliente      ID da tabela clientes
     * @param array  $itensCarrinho   Itens do carrinho (sessão)
     * @param int    $id_endereco     ID do endereço de entrega
     * @param string $metodoPagamento 'pix' ou 'cartao'
     * @param string $cpf             CPF do cliente (para mock de gateway)
     * @return array ['pedido_id' => int, 'pagamento' => array]
     */
    public function executar(
        int    $id_cliente,
        array  $itensCarrinho,
        int    $id_endereco,
        string $metodoPagamento = 'pix',
        string $cpf = ''
    ): array {

        if (empty($itensCarrinho)) {
            throw new \RuntimeException('Seu carrinho está vazio. Adicione produtos antes de finalizar.');
        }

        $produtosVerificados = [];
        $id_artesao          = null;
        $valorTotal          = 0.0;

        foreach ($itensCarrinho as $item) {
            $produto = $this->produtoModel->find((int) $item['produto_id']);

            if ($produto === null) {
                throw new \RuntimeException(
                    "Produto '{$item['nome']}' não encontrado ou foi removido do catálogo."
                );
            }

            if ($produto->estoque <= 0 && $produto->tipo === 'pronta_entrega') {
                throw new \RuntimeException(
                    "O produto '{$produto->nome}' está esgotado e não pode ser comprado. (RN19)"
                );
            }

            if (
                $produto->tipo === 'pronta_entrega' &&
                $produto->estoque < (int) $item['quantidade']
            ) {
                throw new \RuntimeException(
                    "Estoque insuficiente para '{$produto->nome}'. " .
                    "Disponível: {$produto->estoque} | Solicitado: {$item['quantidade']}. (RN19)"
                );
            }

            if ($id_artesao === null) {
                $id_artesao = (int) $produto->artesao_id;
            } elseif ($id_artesao !== (int) $produto->artesao_id) {
                throw new \RuntimeException('Finalize separadamente os produtos de cada artesã.');
            }

            $precoReal   = (float) $produto->preco;
            $quantidade  = (int) $item['quantidade'];
            $valorTotal += round($precoReal * $quantidade, 2);

            $produtosVerificados[] = [
                'produto'    => $produto,
                'preco_real' => $precoReal,
                'quantidade' => $quantidade,
            ];
        }

        // Uma cobrança Pix só pode ser criada com a chave da artesã
        $dadosPix = null;
        if ($metodoPagamento === 'pix') {
            $dadosPix = (new ArtesaoModel())->buscarPorId((int) $id_artesao);
            $pix = new PixService();

            if (!$dadosPix || !$pix->validarChave((string) ($dadosPix->chave_pix ?? ''))
                || trim((string) ($dadosPix->cidade_pix ?? '')) === '') {
                throw new \RuntimeException('A artesã ainda não cadastrou uma chave Pix e cidade válidas. Escolha outra forma de pagamento.');
            }
        }

        $this->db->transStart();

        try {
            // Cria o pedido
            $pedidoId = $this->pedidoModel->insert([
                'id_cliente'     => $id_cliente,
                'id_artesao'     => $id_artesao,
                'id_endereco'    => $id_endereco,
                'valor_total'    => round($valorTotal, 2),
                'status_entrega' => 'em_producao',
            ], true);

            if (!$pedidoId) {
                $erros = $this->pedidoModel->errors();
                throw new \RuntimeException('Erro ao registrar o pedido. ' . implode(' ', $erros));
            }

            // Cria os itens do pedido
            $itensBanco = [];
            foreach ($produtosVerificados as $iv) {
                $itensBanco[] = [
                    'pedido_id'               => (int) $pedidoId,
                    'produto_id'              => (int) $iv['produto']->id,
                    'nome_produto_historico'  => $iv['produto']->nome,
                    'preco_unitario_historico' => $iv['preco_real'],
                    'quantidade'              => $iv['quantidade'],
                    'subtotal'                => round($iv['preco_real'] * $iv['quantidade'], 2),
                ];
            }

            if (!$this->itemPedidoModel->inserirLote($itensBanco)) {
                throw new \RuntimeException('Erro ao registrar os itens do pedido.');
            }

            // Decrementa estoque de produtos pronta_entrega
            foreach ($produtosVerificados as $iv) {
                if ($iv['produto']->tipo === 'pronta_entrega') {
                    $sucesso = $this->produtoModel->decrementarEstoque(
                        (int) $iv['produto']->id,
                        $iv['quantidade']
                    );

                    if (!$sucesso) {
                        throw new \RuntimeException(
                            "Falha na baixa de estoque para '{$iv['produto']->nome}'. " .
                            'O produto pode ter sido esgotado por outro cliente. (RN11)'
                        );
                    }
                }
            }

            // UC08 — Processa pagamento via gateway mock
            $transacaoUseCase = new ProcessarTransacaoFinanceiraUseCase(
                $this->pagamentoModel,
                $this->pedidoModel
            );

            $resultadoPagamento = $transacaoUseCase->processar(
                (int) $pedidoId,
                round($valorTotal, 2),
                $metodoPagamento,
                $cpf
            );

            if ($metodoPagamento === 'pix') {
                $codigoPix = (new PixService())->gerarCodigo(
                    $dadosPix->chave_pix,
                    $dadosPix->nome,
                    $dadosPix->cidade_pix,
                    (float) $valorTotal,
                    (int) $pedidoId
                );

                if (!$this->pagamentoModel->update(
                    $resultadoPagamento['id_pagamento'],
                    ['pix_copia_cola' => $codigoPix]
                )) {
                    throw new \RuntimeException('Não foi possível gerar a cobrança Pix.');
                }
            }

        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw new \RuntimeException($e->getMessage());
        }

        $this->db->transComplete();

        return [
            'pedido_id' => (int) $pedidoId,
            'pagamento' => $resultadoPagamento,
        ];
    }
}
