<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table      = 'clientes';
    protected $primaryKey = 'id_cliente';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_usuario',
        'cpf',
        'data_nascimento',
    ];

    protected $useTimestamps = false;

    /**
     * Busca o cliente completo (dados de usuarios + clientes + enderecos).
     */
    public function buscarComUsuario(int $id_usuario): ?object
    {
        $cliente = $this->db->table('clientes cl')
            ->select('cl.*, u.nome, u.email, u.telefone, u.data_cadastro')
            ->join('usuarios u', 'u.id_usuario = cl.id_usuario')
            ->where('cl.id_usuario', $id_usuario)
            ->get()->getRow();

        if (!$cliente) {
            return null;
        }

        // Carrega endereços
        $cliente->enderecos = $this->db->table('enderecos')
            ->where('id_cliente', $cliente->id_cliente)
            ->orderBy('tipo', 'ASC')
            ->get()->getResultObject();

        $cliente->endereco_completo = '';
        foreach ($cliente->enderecos as $end) {
            if ($end->tipo === 'principal') {
                $cliente->endereco_completo = \App\Models\EnderecoModel::formatar($end);
                break;
            }
        }
        if (empty($cliente->endereco_completo) && !empty($cliente->enderecos)) {
            $cliente->endereco_completo = \App\Models\EnderecoModel::formatar($cliente->enderecos[0]);
        }

        return $cliente;
    }

    /**
     * Busca cliente pelo id_cliente (não pelo id_usuario).
     */
    public function buscarPorId(int $id_cliente): ?object
    {
        return $this->db->table('clientes cl')
            ->select('cl.*, u.nome, u.email, u.telefone')
            ->join('usuarios u', 'u.id_usuario = cl.id_usuario')
            ->where('cl.id_cliente', $id_cliente)
            ->get()->getRow() ?: null;
    }
}
