<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\PagamentoModel;
use App\Models\PedidoModel;

/**
 * UC08 — Processar Transação Financeira
 *
 * Gateway de pagamento simulado (mock):
 *  - PIX direto: aguarda confirmação manual da artesã.
 *  - Cartão: aprovado em 85% dos casos (determinístico via hash do CPF).
 */
class ProcessarTransacaoFinanceiraUseCase
{
    public function __construct(
        private readonly PagamentoModel $pagamentoModel,
        private readonly PedidoModel    $pedidoModel
    ) {}

    /**
     * @param int    $id_pedido  ID do pedido
     * @param float  $valor      Valor a ser cobrado
     * @param string $metodo     'pix' ou 'cartao'
     * @param string $cpf        CPF do comprador (usado no mock de aprovação)
     * @return array ['aprovado' => bool, 'id_pagamento' => int, 'gateway_ref' => string, 'status' => string]
     */
    public function processar(int $id_pedido, float $valor, string $metodo, string $cpf = ''): array
    {
        $gatewayRef = $metodo === 'pix'
            ? 'DIRETO-' . $id_pedido
            : 'MOCK-' . strtoupper(bin2hex(random_bytes(6)));

        // Simulação de aprovação do gateway
        $aprovado = match ($metodo) {
            'pix'    => false,                           // Pix depende do recebimento real
            'cartao' => $this->simularAprovacaoCartao($cpf),
            default  => false,
        };

        $status = $metodo === 'pix' ? 'pendente' : ($aprovado ? 'aprovado' : 'recusado');

        // Verifica se já existe pagamento para o pedido (evita duplicação)
        $pagamentoExistente = $this->pagamentoModel->buscarPorPedido($id_pedido);

        if ($pagamentoExistente) {
            $this->pagamentoModel->atualizarStatus((int) $pagamentoExistente->id_pagamento, $status);
            $id_pagamento = (int) $pagamentoExistente->id_pagamento;
        } else {
            $id_pagamento = (int) $this->pagamentoModel->insert([
                'id_pedido'   => $id_pedido,
                'metodo'      => $metodo,
                'status'      => $status,
                'valor'       => $valor,
                'gateway_ref' => $gatewayRef,
            ], true);
        }

        return [
            'aprovado'    => $aprovado,
            'id_pagamento' => $id_pagamento,
            'gateway_ref' => $gatewayRef,
            'status'      => $status,
        ];
    }

    /**
     * Simula aprovação de cartão de forma determinística (baseada no CPF).
     * 85% de aprovação: reprovado apenas se os últimos 2 dígitos do CPF forem >= 85.
     */
    private function simularAprovacaoCartao(string $cpf): bool
    {
        $cpfNum = (int) preg_replace('/\D/', '', $cpf);
        if ($cpfNum === 0) {
            return true; // Sem CPF, aprova por padrão no mock
        }
        return ($cpfNum % 100) < 85;
    }
}
