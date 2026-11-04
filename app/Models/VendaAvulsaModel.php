<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class VendaAvulsaModel extends Model
{
    protected $table      = 'vendas_avulsas';
    protected $primaryKey = 'id';
    protected $returnType = 'object';

    protected $allowedFields = ['id_artesao', 'produto', 'valor'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
