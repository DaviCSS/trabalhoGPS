<?php

declare(strict_types=1);

namespace App\Services;

use App\Entities\Produto;
use App\Models\ProdutoModel;

/**
 * ProdutoService — Camada de Serviço de Aplicação (CGT / Use Case Gateway)
 *
 * Demonstra a separação de responsabilidades da Clean Architecture:
 * - O Controller apenas chama o Service (não tem lógica de negócio).
 * - O Service orquestra a lógica e delega a regra de domínio à Entidade.
 * - O Model é responsável apenas pela persistência no banco.
 *
 * Fluxo: Controller → Service → Entidade (regra de negócio) → Model (BD)
 */
class ProdutoService
{
    public function __construct(
        private readonly ProdutoModel $produtoModel = new ProdutoModel()
    ) {}

    /**
     * Busca um produto pelo ID e o retorna como Entidade de Domínio.
     *
     * @throws \RuntimeException se o produto não for encontrado
     */
    public function buscarPorId(int $id): Produto
    {
        /** @var Produto|null $produto */
        $produto = $this->produtoModel->find($id);

        if ($produto === null) {
            throw new \RuntimeException("Produto #{$id} não encontrado ou removido do catálogo.");
        }

        return $produto;
    }

    /**
     * Verifica disponibilidade e retorna dados para exibição no carrinho.
     * A regra de "estoque suficiente" fica na Entidade, não aqui.
     *
     * @throws \DomainException (via Entidade) se estoque for insuficiente
     */
    public function verificarDisponibilidadeParaVenda(int $produtoId, int $quantidade): Produto
    {
        $produto = $this->buscarPorId($produtoId);

        // Delegação da regra de negócio para a Entidade de Domínio (RN19)
        if (!$produto->temEstoqueSuficiente($quantidade)) {
            throw new \DomainException(
                "Estoque insuficiente para '{$produto->nome}'. " .
                "Disponível: {$produto->estoque} | Solicitado: {$quantidade}."
            );
        }

        return $produto;
    }

    /**
     * Cadastra um novo produto após validação pelo Model (CI validations).
     * A Entidade garante as invariantes de domínio antes de persistir.
     *
     * @throws \RuntimeException em caso de falha na validação ou persistência
     */
    public function cadastrar(array $dados): int
    {
        // Validação básica de domínio antes de ir ao banco
        $produto = new Produto($dados);

        if ((float) $produto->preco <= 0) {
            throw new \DomainException('O preço do produto deve ser maior que zero.');
        }

        if ((int) $produto->estoque < 0) {
            throw new \DomainException('O estoque não pode ser negativo. (RN11)');
        }

        $id = $this->produtoModel->insert($dados, true);

        if (!$id) {
            $erros = implode(' ', $this->produtoModel->errors());
            throw new \RuntimeException('Falha ao cadastrar produto: ' . $erros);
        }

        return (int) $id;
    }

    /**
     * Edita os dados de um produto existente com validação de propriedade.
     *
     * @throws \RuntimeException se o produto não pertencer à artesã
     */
    public function editar(int $produtoId, int $artesaoId, array $dados): void
    {
        $produto = $this->produtoModel->buscarParaEdicao($produtoId, $artesaoId);

        if ($produto === null) {
            throw new \RuntimeException('Produto não encontrado ou não pertence à sua conta.');
        }

        if (!$this->produtoModel->update($produtoId, $dados)) {
            $erros = implode(' ', $this->produtoModel->errors());
            throw new \RuntimeException('Falha ao editar produto: ' . $erros);
        }
    }
}
