<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class EnderecoModel extends Model
{
    protected $table      = 'enderecos';
    protected $primaryKey = 'id_endereco';
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_cliente',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'tipo',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Lista todos os endereços de um cliente.
     */
    public function listarPorCliente(int $id_cliente): array
    {
        return $this->where('id_cliente', $id_cliente)
                    ->orderBy('tipo', 'ASC')
                    ->findAll();
    }

    /**
     * Retorna o endereço principal do cliente.
     */
    public function buscarPrincipal(int $id_cliente): ?object
    {
        return $this->where('id_cliente', $id_cliente)
                    ->where('tipo', 'principal')
                    ->first();
    }

    /**
     * Formata o endereço como texto para exibição.
     */
    public static function formatar(object $end): string
    {
        $partes = array_filter([
            $end->logradouro ?? '',
            isset($end->numero) ? 'nº ' . $end->numero : '',
            $end->complemento ?? '',
            $end->bairro ?? '',
            $end->cidade ?? '',
            isset($end->estado) ? '- ' . $end->estado : '',
            isset($end->cep) ? 'CEP: ' . $end->cep : '',
        ]);

        return implode(', ', $partes);
    }
}
