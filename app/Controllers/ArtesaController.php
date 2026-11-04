<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\ProdutoModel;
use App\Models\PedidoModel;
use App\Models\CategoriaModel;
use App\Models\ArtesaoModel;
use App\Models\PagamentoModel;
use App\Models\VendaAvulsaModel;
use App\Services\FinanceiroService;
use App\Services\PixService;
use App\UseCases\CadastrarProdutoUseCase;
use App\UseCases\EditarProdutoUseCase;
use CodeIgniter\Controller;

class ArtesaController extends Controller
{
    // Monta o painel com os valores realmente recebidos
    public function dashboard(): string
    {
        $id_artesao   = (int) session()->get('user_perfil_id');
        $produtoModel = new ProdutoModel();
        $financeiro   = new FinanceiroService();
        $total        = $financeiro->consultar($id_artesao, 'total');
        $mes          = $financeiro->consultar($id_artesao, 'mes_atual');

        return view('artesa/dashboard', [
            'usuario'          => (object) [
                'nome'  => session()->get('user_nome'),
                'email' => session()->get('user_email'),
            ],
            'totalProdutos'    => count($produtoModel->listarPorArtesa($id_artesao)),
            'totalPedidos'     => $total['totalVendas'],
            'totalFaturado'    => $total['totalRecebido'],
            'faturamentoMes'   => $mes['totalRecebido'],
            'vendasMes'        => $mes['totalVendas'],
            'pedidosPendentes' => $total['pendentes'],
            'pedidosEntregues' => $total['entregues'],
        ]);
    }

    public function apoio(): string
    {
        $id_artesao = (int) session()->get('user_perfil_id');

        return view('artesa/apoio', [
            'usuario' => (object) [
                'nome'  => session()->get('user_nome'),
                'email' => session()->get('user_email'),
            ],
            'artesao' => (new ArtesaoModel())->find($id_artesao),
        ]);
    }

    public function listarProdutos(): string
    {
        $id_artesao = (int) session()->get('user_perfil_id');
        $produtos   = (new ProdutoModel())->listarPorArtesa($id_artesao);

        return view('artesa/meus_produtos', ['produtos' => $produtos]);
    }

    public function exibirFormCriacao(): string
    {
        $categorias = (new CategoriaModel())->listarParaDropdown();

        return view('artesa/cadastro_produto', ['categorias' => $categorias]);
    }

    // Trata a foto enviada e salva o novo produto na loja
    public function criarProduto()
    {
        $id_artesao = (int) session()->get('user_perfil_id');
        $imagem     = $this->request->getFile('foto');

        $dados = [
            'categoria_id'    => $this->request->getPost('categoria_id'),
            'nome'            => $this->request->getPost('nome'),
            'descricao'       => $this->request->getPost('descricao'),
            'preco'           => $this->request->getPost('preco'),
            'prazo_confeccao' => $this->request->getPost('tipo') === 'pronta_entrega' ? 1 : $this->request->getPost('prazo_confeccao'),
            'estoque'         => $this->request->getPost('tipo') === 'sob_encomenda' ? 0 : $this->request->getPost('estoque'),
            'tipo'            => $this->request->getPost('tipo'),
        ];

        try {
            $useCase = new CadastrarProdutoUseCase(new ProdutoModel());
            $useCase->executar($dados, $id_artesao, $imagem);

            session()->setFlashdata('sucesso', '🧶 Produto publicado com sucesso na sua vitrine!');
            return redirect()->to('/artesa/produtos');

        } catch (\RuntimeException $e) {
            session()->setFlashdata('erro', $e->getMessage());
            return redirect()->to('/artesa/produtos/criar');
        }
    }

    public function exibirFormEdicao(int $id): string
    {
        $id_artesao   = (int) session()->get('user_perfil_id');
        $produtoModel = new ProdutoModel();
        $produto      = $produtoModel->buscarParaEdicao($id, $id_artesao);

        if ($produto === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Produto não encontrado ou você não tem permissão para editá-lo.'
            );
        }

        $categorias = (new CategoriaModel())->listarParaDropdown();

        return view('artesa/editar_produto', [
            'produto'    => $produto,
            'categorias' => $categorias,
        ]);
    }

    // Edita os dados e a foto do produto, garantindo que ele pertence à artesã logada
    public function atualizarProduto(int $id)
    {
        $id_artesao = (int) session()->get('user_perfil_id');
        $imagem     = $this->request->getFile('foto');

        $dados = [
            'categoria_id'    => $this->request->getPost('categoria_id'),
            'nome'            => $this->request->getPost('nome'),
            'descricao'       => $this->request->getPost('descricao'),
            'preco'           => $this->request->getPost('preco'),
            'prazo_confeccao' => $this->request->getPost('tipo') === 'pronta_entrega' ? 1 : $this->request->getPost('prazo_confeccao'),
            'estoque'         => $this->request->getPost('tipo') === 'sob_encomenda' ? 0 : $this->request->getPost('estoque'),
            'tipo'            => $this->request->getPost('tipo'),
        ];

        try {
            $useCase = new EditarProdutoUseCase(new ProdutoModel());
            $useCase->executar($id, $dados, $id_artesao, $imagem);

            session()->setFlashdata('sucesso', 'Produto atualizado com sucesso!');
            return redirect()->to('/artesa/produtos');

        } catch (\RuntimeException $e) {
            session()->setFlashdata('erro', $e->getMessage());
            return redirect()->to("/artesa/produtos/editar/{$id}");
        }
    }

    // Remove o produto da vitrine
    public function excluirProduto(int $id)
    {
        $id_artesao = (int) session()->get('user_perfil_id');

        try {
            $useCase = new EditarProdutoUseCase(new ProdutoModel());
            $useCase->excluir($id, $id_artesao);

            session()->setFlashdata('sucesso', 'Produto removido da vitrine com sucesso.');

        } catch (\RuntimeException $e) {
            session()->setFlashdata('erro', $e->getMessage());
        }

        return redirect()->to('/artesa/produtos');
    }

    public function listarPedidos(): string
    {
        $id_artesao = (int) session()->get('user_perfil_id');
        $pedidos    = (new PedidoModel())->listarPorArtesa($id_artesao);

        return view('artesa/pedidos', ['pedidos' => $pedidos]);
    }

    // Muda a etapa do pedido (ex: de 'em produção' para 'enviado aos correios')
    public function atualizarStatusPedido(int $id)
    {
        $novoStatus  = $this->request->getPost('status_entrega');
        $pedidoModel = new PedidoModel();

        $pedido = $pedidoModel->find($id);
        if ($pedido === null || (int) $pedido->id_artesao !== (int) session()->get('user_perfil_id')) {
            session()->setFlashdata('erro', 'Ação não autorizada. (RN15)');
            return redirect()->to('/artesa/pedidos');
        }

        // Não avança a entrega enquanto o pagamento estiver pendente
        $pagamento = (new PagamentoModel())->buscarPorPedido($id);
        if ($novoStatus !== 'cancelado' && (!$pagamento || $pagamento->status !== 'aprovado')) {
            session()->setFlashdata('erro', 'Confirme o recebimento antes de avançar o pedido.');
            return redirect()->to('/artesa/pedidos');
        }

        // Atualiza código de rastreio se enviado
        $codigoRastreio = trim($this->request->getPost('codigo_rastreio') ?? '');
        $updateData     = ['status_entrega' => $novoStatus];

        if ($codigoRastreio !== '') {
            $updateData['codigo_rastreio'] = $codigoRastreio;
        }

        if (in_array($novoStatus, ['em_producao', 'aguardando_coleta', 'enviado', 'entregue', 'cancelado'], true)) {
            $pedidoModel->update($id, $updateData);
            session()->setFlashdata('sucesso', 'Status do pedido atualizado com sucesso!');
        } else {
            session()->setFlashdata('erro', 'Status inválido. Tente novamente.');
        }

        return redirect()->to('/artesa/pedidos');
    }

    // PBI-03: cadastra uma venda já realizada em poucos campos
    public function registrarVenda()
    {
        $id_artesao = (int) session()->get('user_perfil_id');
        $produto = trim((string) $this->request->getPost('produto'));
        $valor = trim((string) $this->request->getPost('valor'));

        if ($produto === '' || mb_strlen($produto) > 255
            || !preg_match('/^\d{1,8}(\.\d{1,2})?$/', $valor) || (float) $valor <= 0) {
            session()->setFlashdata('erro', 'Informe o nome da peça e um valor maior que zero.');
            return redirect()->to('/artesa/dashboard');
        }

        $id = (new VendaAvulsaModel())->insert([
            'id_artesao' => $id_artesao,
            'produto'    => $produto,
            'valor'      => round((float) $valor, 2),
        ], true);

        session()->setFlashdata($id ? 'sucesso' : 'erro', $id
            ? 'Venda registrada! Seu saldo e histórico foram atualizados.'
            : 'Não foi possível registrar a venda.');

        return redirect()->to('/artesa/dashboard');
    }

    // PBI-07: vincula a chave Pix somente ao perfil logado
    public function salvarChavePix()
    {
        $id_artesao = (int) session()->get('user_perfil_id');
        $chave = trim((string) $this->request->getPost('chave_pix'));
        $cidade = trim((string) $this->request->getPost('cidade_pix'));

        if (!(new PixService())->validarChave($chave) || mb_strlen($chave) > 77
            || mb_strlen($cidade) < 2 || mb_strlen($cidade) > 60
            || !preg_match('/^[\p{L}\s-]+$/u', $cidade)) {
            session()->setFlashdata('erro', 'Informe uma chave Pix e uma cidade válidas.');
            return redirect()->to('/artesa/apoio');
        }

        $salvo = (new ArtesaoModel())->update($id_artesao, [
            'chave_pix' => $chave,
            'cidade_pix' => $cidade,
        ]);

        session()->setFlashdata($salvo ? 'sucesso' : 'erro',
            $salvo ? 'Chave Pix salva! As novas cobranças usarão essa chave.' : 'Erro ao salvar a chave Pix.');

        return redirect()->to('/artesa/apoio');
    }

    // Confirma um Pix pendente sem depender de gateway
    public function confirmarPagamento(int $id)
    {
        $id_artesao = (int) session()->get('user_perfil_id');
        $pedido = (new PedidoModel())->find($id);
        $pagamentoModel = new PagamentoModel();
        $pagamento = $pagamentoModel->buscarPorPedido($id);

        if (!$pedido || (int) $pedido->id_artesao !== $id_artesao
            || $pedido->status_entrega === 'cancelado'
            || !$pagamento || $pagamento->metodo !== 'pix'
            || $pagamento->status !== 'pendente') {
            session()->setFlashdata('erro', 'Não é possível confirmar esse pagamento.');
            return redirect()->to('/artesa/pedidos');
        }

        // A artesã precisa ter conferido o recebimento no banco.
        if ($this->request->getPost('conferiu_extrato') !== 'sim') {
            session()->setFlashdata('erro', 'Confira o extrato no aplicativo do banco antes de confirmar o Pix.');
            return redirect()->to('/artesa/pedidos');
        }

        if ($pagamentoModel->atualizarStatus((int) $pagamento->id_pagamento, 'aprovado')) {
            session()->setFlashdata('sucesso', 'Recebimento confirmado! A venda já aparece no saldo.');
        } else {
            session()->setFlashdata('erro', 'Não foi possível confirmar o pagamento.');
        }

        return redirect()->to('/artesa/pedidos');
    }

    public function relatorio(): string
    {
        $id_artesao = (int) session()->get('user_perfil_id');
        $periodo = (string) ($this->request->getGet('periodo') ?? 'mes_atual');
        $mes = (string) ($this->request->getGet('mes') ?? '');
        $resumo = (new FinanceiroService())->consultar($id_artesao, $periodo, $mes);

        return view('artesa/relatorio_vendas', [
            'resumo'   => $resumo,
            'periodo'  => $periodo,
            'mes'      => $mes,
        ]);
    }

    // PBI-15: download de um resumo simples, sem biblioteca paga de PDF
    public function exportarRelatorio()
    {
        $id_artesao = (int) session()->get('user_perfil_id');
        $periodo = (string) ($this->request->getGet('periodo') ?? 'mes_atual');
        $mes = (string) ($this->request->getGet('mes') ?? '');
        $resumo = (new FinanceiroService())->consultar($id_artesao, $periodo, $mes);

        $linhas = [
            'CROCHETTEI - RELATORIO FINANCEIRO',
            'Periodo: ' . $resumo['periodo'],
            'Vendas recebidas: ' . $resumo['totalVendas'],
            'Total recebido: R$ ' . number_format($resumo['totalRecebido'], 2, ',', '.'),
            'Pedidos aguardando pagamento: ' . $resumo['pendentes'],
            '',
            'DATA | DESCRICAO | SITUACAO | VALOR',
        ];

        foreach ($resumo['registros'] as $registro) {
            $linhas[] = date('d/m/Y', strtotime($registro['data'])) . ' | '
                . str_replace(["\r", "\n", '|'], ' ', $registro['produto']) . ' | '
                . $registro['situacao'] . ' | R$ '
                . number_format($registro['valor'], 2, ',', '.');
        }

        $texto = implode("\r\n", $linhas) . "\r\n";
        return $this->response
            ->setHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="relatorio-crochettei.txt"')
            ->setBody($texto);
    }
}
