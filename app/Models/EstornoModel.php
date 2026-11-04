<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class EstornoModel extends Model
{
    protected $table      = 'estornos';
    protected $primaryKey = 'id_estorno';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_pagamento',
        'valor',
        'motivo',
        'data_estorno',
    ];

    protected $useTimestamps = false;

    public function listarPorPagamento(int $id_pagamento): array
    {
        return $this->where('id_pagamento', $id_pagamento)->findAll();
    }
}
