<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed principal da aplicação Crochettei.
     * Chama os seeders secundários na ordem correta respeitando as FKs.
     */
    public function run()
    {
        // 1. Categorias (Sem dependências)
        $this->call('CategoriaSeeder');

        // 2. Usuários (Artesãs e Clientes - Sem dependências)
        $this->call('UsuarioSeeder');

        // 3. Produtos (Depende de Usuários e Categorias)
        $this->call('ProdutoSeeder');

        // 4. Pedidos e Itens (Depende de Usuários e Produtos)
        $this->call('PedidoSeeder');

        echo "Seeders executados com sucesso!\n";
    }
}
