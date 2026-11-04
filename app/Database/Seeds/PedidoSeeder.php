<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PedidoSeeder extends Seeder
{
    public function run()
    {
        $db          = \Config\Database::connect();
        $pedidoModel = new \App\Models\PedidoModel();
        $itemModel   = new \App\Models\ItemPedidoModel();

        // Buscar clientes (id_cliente + endereco principal)
        $fernanda = $db->table('clientes c')
            ->select('c.id_cliente')
            ->join('usuarios u', 'u.id_usuario = c.id_usuario')
            ->where('u.email', 'fernanda.costa@email.com')
            ->get()->getRow();

        $ana = $db->table('clientes c')
            ->select('c.id_cliente')
            ->join('usuarios u', 'u.id_usuario = c.id_usuario')
            ->where('u.email', 'ana.compras@exemplo.com')
            ->get()->getRow();

        // Buscar endereços principais de cada cliente
        $endFernanda = $db->table('enderecos')
            ->where('id_cliente', $fernanda->id_cliente)
            ->where('tipo', 'principal')
            ->get()->getRow();

        $endAna = $db->table('enderecos')
            ->where('id_cliente', $ana->id_cliente)
            ->where('tipo', 'principal')
            ->get()->getRow();

        // Buscar artesãs
        $artesaMaria = $db->table('artesaos a')
            ->select('a.id_artesao')
            ->join('usuarios u', 'u.id_usuario = a.id_usuario')
            ->where('u.email', 'maria.artesa@crochettei.com')
            ->get()->getRow();

        $artesaLurdes = $db->table('artesaos a')
            ->select('a.id_artesao')
            ->join('usuarios u', 'u.id_usuario = a.id_usuario')
            ->where('u.email', 'lourdes.oliveira@email.com')
            ->get()->getRow();

        $artesaFatima = $db->table('artesaos a')
            ->select('a.id_artesao')
            ->join('usuarios u', 'u.id_usuario = a.id_usuario')
            ->where('u.email', 'fatima.silva@email.com')
            ->get()->getRow();

        // Buscar produtos
        $produtoModel = new \App\Models\ProdutoModel();
        $urso   = $produtoModel->where('nome', 'Urso de Pelúcia Amigurumi')->first();
        $tapete = $produtoModel->where('nome', 'Tapete de Barbante Cru')->first();
        $blusa  = $produtoModel->where('nome', 'Blusa de Frio em Crochê')->first();
        $polvo  = $produtoModel->where('nome', 'Polvo Amigurumi para Prematuros')->first();

        if (!$fernanda || !$ana || !$urso || !$tapete || !$blusa || !$polvo) {
            echo "Aviso: Dependências para PedidoSeeder não encontradas.\n";
            return;
        }

        if ($pedidoModel->countAllResults() > 0) {
            return;
        }

        $agora = date('Y-m-d H:i:s');

        // --- PEDIDO 1: Fernanda compra Urso da Maria (Aprovado, em produção) ---
        $pedido1_id = $pedidoModel->insert([
            'id_cliente'     => $fernanda->id_cliente,
            'id_artesao'     => $artesaMaria->id_artesao,
            'id_endereco'    => $endFernanda->id_endereco,
            'valor_total'    => $urso->preco,
            'status_entrega' => 'em_producao',
        ], true);

        $itemModel->insert([
            'pedido_id'               => $pedido1_id,
            'produto_id'              => $urso->id,
            'nome_produto_historico'  => $urso->nome,
            'preco_unitario_historico' => $urso->preco,
            'quantidade'              => 1,
            'subtotal'                => $urso->preco,
        ]);

        $db->table('pagamentos')->insert([
            'id_pedido'   => $pedido1_id,
            'metodo'      => 'pix',
            'status'      => 'aprovado',
            'valor'       => $urso->preco,
            'gateway_ref' => 'MOCK-' . strtoupper(bin2hex(random_bytes(4))),
            'created_at'  => $agora,
            'updated_at'  => $agora,
        ]);

        // --- PEDIDO 2: Ana compra Tapete da Maria (Pendente) ---
        $pedido2_id = $pedidoModel->insert([
            'id_cliente'     => $ana->id_cliente,
            'id_artesao'     => $artesaMaria->id_artesao,
            'id_endereco'    => $endAna->id_endereco,
            'valor_total'    => $tapete->preco,
            'status_entrega' => 'em_producao',
        ], true);

        $itemModel->insert([
            'pedido_id'               => $pedido2_id,
            'produto_id'              => $tapete->id,
            'nome_produto_historico'  => $tapete->nome,
            'preco_unitario_historico' => $tapete->preco,
            'quantidade'              => 1,
            'subtotal'                => $tapete->preco,
        ]);

        $db->table('pagamentos')->insert([
            'id_pedido'   => $pedido2_id,
            'metodo'      => 'cartao',
            'status'      => 'pendente',
            'valor'       => $tapete->preco,
            'gateway_ref' => null,
            'created_at'  => $agora,
            'updated_at'  => $agora,
        ]);

        // --- PEDIDO 3: Fernanda compra Blusa da Lourdes (Aprovado, Entregue) ---
        $pedido3_id = $pedidoModel->insert([
            'id_cliente'     => $fernanda->id_cliente,
            'id_artesao'     => $artesaLurdes->id_artesao,
            'id_endereco'    => $endFernanda->id_endereco,
            'valor_total'    => $blusa->preco,
            'status_entrega' => 'entregue',
        ], true);

        $itemModel->insert([
            'pedido_id'               => $pedido3_id,
            'produto_id'              => $blusa->id,
            'nome_produto_historico'  => $blusa->nome,
            'preco_unitario_historico' => $blusa->preco,
            'quantidade'              => 1,
            'subtotal'                => $blusa->preco,
        ]);

        $db->table('pagamentos')->insert([
            'id_pedido'   => $pedido3_id,
            'metodo'      => 'pix',
            'status'      => 'aprovado',
            'valor'       => $blusa->preco,
            'gateway_ref' => 'MOCK-' . strtoupper(bin2hex(random_bytes(4))),
            'created_at'  => $agora,
            'updated_at'  => $agora,
        ]);

        // --- PEDIDO 4: Ana compra 2 Polvos da Fátima (Aprovado, Enviado) ---
        $valorPolvos = $polvo->preco * 2;
        $pedido4_id  = $pedidoModel->insert([
            'id_cliente'     => $ana->id_cliente,
            'id_artesao'     => $artesaFatima->id_artesao,
            'id_endereco'    => $endAna->id_endereco,
            'valor_total'    => $valorPolvos,
            'status_entrega' => 'enviado',
        ], true);

        $itemModel->insert([
            'pedido_id'               => $pedido4_id,
            'produto_id'              => $polvo->id,
            'nome_produto_historico'  => $polvo->nome,
            'preco_unitario_historico' => $polvo->preco,
            'quantidade'              => 2,
            'subtotal'                => $valorPolvos,
        ]);

        $db->table('pagamentos')->insert([
            'id_pedido'   => $pedido4_id,
            'metodo'      => 'cartao',
            'status'      => 'aprovado',
            'valor'       => $valorPolvos,
            'gateway_ref' => 'MOCK-' . strtoupper(bin2hex(random_bytes(4))),
            'created_at'  => $agora,
            'updated_at'  => $agora,
        ]);
    }
}
