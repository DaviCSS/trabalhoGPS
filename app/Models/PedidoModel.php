<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table      = 'pedidos';
    protected $primaryKey = 'id';
    protected $returnType = 'object';

    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_cliente',
        'id_artesao',
        'id_endereco',
        'codigo_rastreio',
        'valor_total',
        'status_entrega',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'id_cliente' => [
            'label'  => 'Cliente',
            'rules'  => 'required|integer',
            'errors' => ['required' => 'O pedido deve ter um cliente associado.'],
        ],
        'id_artesao' => [
            'label'  => 'Artesã',
            'rules'  => 'required|integer',
            'errors' => ['required' => 'O pedido deve ter uma artesã associada.'],
        ],
        'id_endereco' => [
            'label'  => 'Endereço',
            'rules'  => 'required|integer',
            'errors' => ['required' => 'O endereço de entrega é obrigatório.'],
        ],
        'valor_total' => [
            'label'  => 'Valor Total',
            'rules'  => 'required|decimal|greater_than[0]',
            'errors' => [
                'required'     => 'O valor total do pedido é obrigatório.',
                'decimal'      => 'O valor total deve ser numérico.',
                'greater_than' => 'O valor total deve ser maior que zero.',
            ],
        ],
    ];

    /**
     * Traz todos os pedidos feitos por um cliente específico
     */
    public function listarPorCliente(int $clienteId): array
    {
        return $this->db->table('pedidos pe')
            ->select('
                pe.*,
                u_artesao.nome AS artesa_nome,
                u_artesao.telefone AS artesa_telefone,
                e.logradouro, e.numero, e.bairro, e.cidade, e.estado, e.cep,
                pg.status AS status_pagamento, pg.metodo AS metodo_pagamento, pg.id_pagamento
            ')
            ->join('artesaos a',    'a.id_artesao = pe.id_artesao',     'left')
            ->join('usuarios u_artesao', 'u_artesao.id_usuario = a.id_usuario', 'left')
            ->join('enderecos e',   'e.id_endereco = pe.id_endereco',    'left')
            ->join('pagamentos pg', 'pg.id_pedido = pe.id',              'left')
            ->where('pe.id_cliente', $clienteId)
            ->orderBy('pe.created_at', 'DESC')
            ->get()
            ->getResultObject();
    }

    public function listarPorArtesa(int $artesaoId): array
    {
        return $this->db->table('pedidos pe')
            ->select('
                pe.*,
                u_cliente.nome AS cliente_nome,
                u_cliente.telefone AS cliente_telefone,
                e.logradouro, e.numero, e.bairro, e.cidade, e.estado, e.cep,
                pg.status AS status_pagamento, pg.metodo AS metodo_pagamento, pg.id_pagamento
            ')
            ->join('clientes cl',   'cl.id_cliente = pe.id_cliente',    'left')
            ->join('usuarios u_cliente', 'u_cliente.id_usuario = cl.id_usuario', 'left')
            ->join('enderecos e',   'e.id_endereco = pe.id_endereco',    'left')
            ->join('pagamentos pg', 'pg.id_pedido = pe.id',              'left')
            ->where('pe.id_artesao', $artesaoId)
            ->orderBy('pe.created_at', 'DESC')
            ->get()
            ->getResultObject();
    }

    /**
     * Puxa o pedido junto com as peças que estão dentro dele
     */
    public function buscarComItens(int $pedidoId): ?object
    {
        $pedido = $this->find($pedidoId);

        if ($pedido === null) {
            return null;
        }

        $pedido->itens = $this->db->table('itens_pedido ip')
            ->select('ip.*, p.nome AS produto_nome, p.imagem_path')
            ->join('produtos p', 'p.id = ip.produto_id', 'left')
            ->where('ip.pedido_id', $pedidoId)
            ->get()
            ->getResultObject();

        return $pedido;
    }

    public function atualizarStatusEntrega(int $pedidoId, string $novoStatus): bool
    {
        $statusValidos = ['em_producao', 'aguardando_coleta', 'enviado', 'entregue', 'cancelado'];

        if (!in_array($novoStatus, $statusValidos, true)) {
            return false;
        }

        return $this->update($pedidoId, ['status_entrega' => $novoStatus]);
    }

    public function metricsArtesa(int $artesaoId): object
    {
        return $this->db->table('pedidos pe')
            ->select('
                COUNT(*) AS total_pedidos,
                SUM(pe.valor_total) AS total_faturado,
                SUM(CASE WHEN pg.status = "pendente" OR pg.status IS NULL THEN 1 ELSE 0 END) AS pedidos_pendentes,
                SUM(CASE WHEN pe.status_entrega = "entregue" THEN 1 ELSE 0 END) AS pedidos_entregues
            ')
            ->join('pagamentos pg', 'pg.id_pedido = pe.id', 'left')
            ->where('pe.id_artesao', $artesaoId)
            ->get()
            ->getRowObject();
    }

    public function metricsGlobais(): object
    {
        return $this->db->table('pedidos pe')
            ->select('
                COUNT(*) AS total_pedidos,
                SUM(pe.valor_total) AS total_faturado,
                SUM(CASE WHEN pg.status = "pendente" OR pg.status IS NULL THEN 1 ELSE 0 END) AS pedidos_pendentes,
                SUM(CASE WHEN pe.status_entrega = "entregue" THEN 1 ELSE 0 END) AS pedidos_entregues
            ')
            ->join('pagamentos pg', 'pg.id_pedido = pe.id', 'left')
            ->get()
            ->getRowObject();
    }

    public function listarTodos(): array
    {
        return $this->db->table('pedidos pe')
            ->select('
                pe.*,
                u_cliente.nome AS cliente_nome,
                u_artesao.nome AS artesa_nome,
                e.logradouro, e.numero, e.cidade, e.estado,
                pg.status AS status_pagamento, pg.metodo AS metodo_pagamento, pg.id_pagamento
            ')
            ->join('clientes cl',        'cl.id_cliente = pe.id_cliente',          'left')
            ->join('usuarios u_cliente', 'u_cliente.id_usuario = cl.id_usuario',   'left')
            ->join('artesaos a',         'a.id_artesao = pe.id_artesao',           'left')
            ->join('usuarios u_artesao', 'u_artesao.id_usuario = a.id_usuario',    'left')
            ->join('enderecos e',        'e.id_endereco = pe.id_endereco',         'left')
            ->join('pagamentos pg',      'pg.id_pedido = pe.id',                   'left')
            ->orderBy('pe.created_at', 'DESC')
            ->get()
            ->getResultObject();
    }

    /**
     * UC11 — Rastrear encomenda pelo código de rastreio
     */
    public function buscarPorRastreio(string $codigo): ?object
    {
        return $this->db->table('pedidos pe')
            ->select('pe.*, u_artesao.nome AS artesa_nome')
            ->join('artesaos a',         'a.id_artesao = pe.id_artesao',          'left')
            ->join('usuarios u_artesao', 'u_artesao.id_usuario = a.id_usuario',   'left')
            ->where('pe.codigo_rastreio', $codigo)
            ->get()
            ->getRowObject() ?: null;
    }
}
