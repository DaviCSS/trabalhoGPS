<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class ItemPedidoModel extends Model
{

    protected $table      = 'itens_pedido';
    protected $primaryKey = 'id';
    protected $returnType = 'object';

    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'pedido_id',
        'produto_id',
        'nome_produto_historico',
        'preco_unitario_historico',
        'quantidade',
        'subtotal',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'pedido_id' => [
            'label'  => 'Pedido',
            'rules'  => 'required|integer',
            'errors' => ['required' => 'O item deve estar vinculado a um pedido.'],
        ],

        'produto_id' => [
            'label'  => 'Produto',
            'rules'  => 'permit_empty|integer',
        ],
        'nome_produto_historico' => [
            'label'  => 'Nome Histórico do Produto',
            'rules'  => 'required|min_length[1]|max_length[255]',
            'errors' => ['required' => 'O nome histórico do produto é obrigatório (congela o nome no ato da venda).'],
        ],
        'preco_unitario_historico' => [
            'label'  => 'Preço Unitário Histórico',
            'rules'  => 'required|decimal|greater_than[0]',
            'errors' => [
                'required'     => 'O preço unitário histórico é obrigatório.',
                'decimal'      => 'O preço unitário deve ser numérico.',
                'greater_than' => 'O preço unitário deve ser maior que zero.',
            ],
        ],
        'quantidade' => [
            'label'  => 'Quantidade',
            'rules'  => 'required|integer|greater_than[0]',
            'errors' => [
                'required'     => 'A quantidade é obrigatória.',
                'integer'      => 'A quantidade deve ser um número inteiro.',
                'greater_than' => 'A quantidade mínima é 1.',
            ],
        ],
        'subtotal' => [
            'label'  => 'Subtotal',
            'rules'  => 'required|decimal|greater_than[0]',
            'errors' => [
                'required'     => 'O subtotal é obrigatório.',
                'decimal'      => 'O subtotal deve ser numérico.',
                'greater_than' => 'O subtotal deve ser maior que zero.',
            ],
        ],
    ];

    public function inserirLote(array $itens): bool
    {

        return $this->insertBatch($itens) !== false;
    }

    public function listarPorPedido(int $pedidoId): array
    {
        return $this->db->table('itens_pedido ip')
            ->select('ip.*, p.nome AS produto_nome, p.imagem_path, p.tipo AS produto_tipo')
            ->join('produtos p', 'p.id = ip.produto_id', 'left')
            ->where('ip.pedido_id', $pedidoId)
            ->get()
            ->getResultObject();
    }
}
