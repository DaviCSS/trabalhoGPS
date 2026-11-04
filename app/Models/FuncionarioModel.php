<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class FuncionarioModel extends Model
{
    protected $table      = 'funcionarios';
    protected $primaryKey = 'id_funcionario';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_usuario',
        'cargo',
        'setor',
    ];

    protected $useTimestamps = false;

    public function buscarComUsuario(int $id_usuario): ?object
    {
        return $this->db->table('funcionarios f')
            ->select('f.*, u.nome, u.email, u.telefone')
            ->join('usuarios u', 'u.id_usuario = f.id_usuario')
            ->where('f.id_usuario', $id_usuario)
            ->get()->getRow() ?: null;
    }
}
