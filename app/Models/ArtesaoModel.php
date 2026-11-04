<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class ArtesaoModel extends Model
{
    protected $table      = 'artesaos';
    protected $primaryKey = 'id_artesao';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_usuario',
        'biografia',
        'portfolio',
    ];

    protected $useTimestamps = false;

    /**
     * Busca o artesão completo (dados de usuarios + artesaos).
     */
    public function buscarComUsuario(int $id_usuario): ?object
    {
        return $this->db->table('artesaos a')
            ->select('a.*, u.nome, u.email, u.telefone, u.data_cadastro')
            ->join('usuarios u', 'u.id_usuario = a.id_usuario')
            ->where('a.id_usuario', $id_usuario)
            ->get()->getRow() ?: null;
    }

    /**
     * Busca artesão pelo id_artesao.
     */
    public function buscarPorId(int $id_artesao): ?object
    {
        return $this->db->table('artesaos a')
            ->select('a.*, u.nome, u.email, u.telefone')
            ->join('usuarios u', 'u.id_usuario = a.id_usuario')
            ->where('a.id_artesao', $id_artesao)
            ->get()->getRow() ?: null;
    }
}
