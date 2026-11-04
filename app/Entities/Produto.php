<?php

declare(strict_types=1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;

/**
 * Entidade Produto — Camada de Domínio (CDP)
 *
 * Encapsula os atributos do produto e as regras de negócio de domínio,
 * demonstrando a separação de responsabilidades da Clean Architecture.
 *
 * Responsabilidade: APENAS regras de negócio do domínio "Produto".
 * NÃO acessa banco de dados, NÃO lida com HTTP.
 */
class Produto extends Entity
{
    protected $attributes = [
        'id'              => null,
        'artesao_id'      => null,
        'categoria_id'    => null,
        'nome'            => null,
        'descricao'       => null,
        'preco'           => null,
        'prazo_confeccao' => null,
        'estoque'         => 0,
        'tipo'            => 'pronta_entrega',
        'imagem_path'     => null,
    ];

    protected $casts = [
        'id'              => 'integer',
        'artesao_id'      => 'integer',
        'categoria_id'    => 'integer',
        'preco'           => 'float',
        'prazo_confeccao' => 'integer',
        'estoque'         => 'integer',
    ];

    // =========================================================================
    // Regras de Negócio de Domínio (RN)
    // =========================================================================

    /**
     * RN19 — Verifica se há unidades suficientes para atender a demanda.
     * Produtos sob encomenda sempre estão disponíveis (não têm estoque físico).
     */
    public function temEstoqueSuficiente(int $quantidadeDesejada): bool
    {
        if (in_array($this->tipo, ['sob_encomenda', 'misto'], true)) {
            return true;
        }

        return $this->estoque >= $quantidadeDesejada;
    }

    /**
     * RN11 — Decrementa o estoque e lança exceção se ficar negativo.
     * Esta é a INVARIANTE DE DOMÍNIO central: estoque NUNCA pode ser negativo.
     *
     * @throws \DomainException se a quantidade solicitada exceder o estoque atual
     */
    public function decrementarEstoque(int $quantidade): void
    {
        if ($this->tipo !== 'pronta_entrega') {
            return; // Sob encomenda não controla estoque físico
        }

        $novoEstoque = $this->estoque - $quantidade;

        if ($novoEstoque < 0) {
            throw new \DomainException(
                "Estoque insuficiente para '{$this->nome}'. " .
                "Disponível: {$this->estoque} | Solicitado: {$quantidade}. (RN11/RN19)"
            );
        }

        $this->attributes['estoque'] = $novoEstoque;
    }

    /**
     * RN07 — Retorna true se o produto está apto para venda imediata.
     */
    public function estaDisponivel(): bool
    {
        if ($this->tipo !== 'pronta_entrega') {
            return true;
        }

        return $this->estoque > 0;
    }

    /**
     * Retorna o preço formatado no padrão brasileiro para exibição em View.
     */
    public function getPrecoFormatado(): string
    {
        return 'R$ ' . number_format((float) $this->preco, 2, ',', '.');
    }
}
