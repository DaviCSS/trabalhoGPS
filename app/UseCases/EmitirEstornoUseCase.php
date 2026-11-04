<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\EstornoModel;
use App\Models\PagamentoModel;

/**
 * UC09 — Emitir Estorno
 * Cria um registro na tabela estornos e atualiza o status do pagamento.
 */
class EmitirEstornoUseCase
{
    public function __construct(
        private readonly EstornoModel   $estornoModel,
        private readonly PagamentoModel $pagamentoModel
    ) {}

    /**
     * @param int    $id_pagamento  ID do pagamento a estornar
     * @param float  $valor         Valor do estorno (pode ser parcial)
     * @param string $motivo        Justificativa do estorno
     * @return int ID do estorno criado
     */
    public function executar(int $id_pagamento, float $valor, string $motivo): int
    {
        $pagamento = $this->pagamentoModel->find($id_pagamento);

        if ($pagamento === null) {
            throw new \RuntimeException('Pagamento não encontrado. (UC09)');
        }

        if ($pagamento->status !== 'aprovado') {
            throw new \RuntimeException(
                "Não é possível estornar um pagamento com status '{$pagamento->status}'. " .
                'Apenas pagamentos aprovados podem ser estornados. (UC09)'
            );
        }

        if ($valor <= 0 || $valor > (float) $pagamento->valor) {
            throw new \RuntimeException(
                'Valor do estorno inválido. Deve ser maior que zero e não superior ao valor pago. (UC09)'
            );
        }

        // Cria o registro de estorno
        $id_estorno = $this->estornoModel->insert([
            'id_pagamento' => $id_pagamento,
            'valor'        => $valor,
            'motivo'       => trim($motivo),
            'data_estorno' => date('Y-m-d H:i:s'),
        ], true);

        // Atualiza o status do pagamento
        $this->pagamentoModel->atualizarStatus($id_pagamento, 'estornado');

        return (int) $id_estorno;
    }
}
