<?php

declare(strict_types=1);

namespace App\UseCases;

use CodeIgniter\Session\Session;

class GerenciarCarrinhoUseCase
{
    public function __construct(
        private readonly Session $session
    ) {}

    // Adiciona o produto na sacola. Se o produto for de outra artesã, a sacola é zerada primeiro
    public function adicionar(array $dadosProduto, int $quantidade = 1): void
    {
        $carrinho   = $this->obterCarrinho();
        $produtoId  = (int) $dadosProduto['produto_id'];
        $artesaoId  = (int) $dadosProduto['artesao_id'];

        if (!empty($carrinho)) {
            $artesaoAtual = $this->obterArtesaoIdDoCarrinho();

            if ($artesaoAtual !== null && $artesaoAtual !== $artesaoId) {

                $this->limpar();
                $carrinho = [];
            }
        }

        if (isset($carrinho[$produtoId])) {

            $carrinho[$produtoId]['quantidade'] += $quantidade;
        } else {

            $carrinho[$produtoId] = [
                'produto_id'  => $produtoId,
                'nome'        => $dadosProduto['nome'],
                'preco'       => (float) $dadosProduto['preco'],
                'artesao_id'  => $artesaoId,
                'imagem_path' => $dadosProduto['imagem_path'] ?? null,
                'quantidade'  => $quantidade,
            ];
        }

        $carrinho[$produtoId]['subtotal'] = round(
            $carrinho[$produtoId]['preco'] * $carrinho[$produtoId]['quantidade'],
            2
        );

        $this->session->set('carrinho', $carrinho);
    }

    public function remover(int $produtoId): void
    {
        $carrinho = $this->obterCarrinho();
        unset($carrinho[$produtoId]);
        $this->session->set('carrinho', $carrinho);
    }

    public function atualizarQuantidade(int $produtoId, int $quantidade): void
    {
        if ($quantidade <= 0) {
            $this->remover($produtoId);
            return;
        }

        $carrinho = $this->obterCarrinho();

        if (isset($carrinho[$produtoId])) {
            $carrinho[$produtoId]['quantidade'] = $quantidade;
            $carrinho[$produtoId]['subtotal']   = round(
                $carrinho[$produtoId]['preco'] * $quantidade,
                2
            );
            $this->session->set('carrinho', $carrinho);
        }
    }

    public function obterCarrinho(): array
    {
        return $this->session->get('carrinho') ?? [];
    }

    public function calcularTotal(): float
    {
        $carrinho = $this->obterCarrinho();

        return round(array_sum(array_column($carrinho, 'subtotal')), 2);
    }

    public function contarItens(): int
    {
        $carrinho = $this->obterCarrinho();

        return (int) array_sum(array_column($carrinho, 'quantidade'));
    }

    public function limpar(): void
    {
        $this->session->remove('carrinho');
    }

    public function estaVazio(): bool
    {
        return empty($this->obterCarrinho());
    }

    private function obterArtesaoIdDoCarrinho(): ?int
    {
        $carrinho = $this->obterCarrinho();

        if (empty($carrinho)) {
            return null;
        }

        $primeiro = reset($carrinho);

        $artesaoId = $primeiro['artesao_id'] ?? null;
        return $artesaoId !== null ? (int) $artesaoId : null;
    }
}
