<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class PagamentoModel extends Model
{
    protected $table      = 'pagamentos';
    protected $primaryKey = 'id_pagamento';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_pedido',
        'metodo',
        'status',
        'valor',
        'gateway_ref',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Busca o pagamento de um pedido específico.
     */
    public function buscarPorPedido(int $id_pedido): ?object
    {
        return $this->where('id_pedido', $id_pedido)->first();
    }

    /**
     * Atualiza o status do pagamento.
     */
    public function atualizarStatus(int $id_pagamento, string $novoStatus): bool
    {
        $validos = ['pendente', 'aprovado', 'recusado', 'estornado'];
        if (!in_array($novoStatus, $validos, true)) {
            return false;
        }

        return $this->update($id_pagamento, ['status' => $novoStatus]);
    }
}
