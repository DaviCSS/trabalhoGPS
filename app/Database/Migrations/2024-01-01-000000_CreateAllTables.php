<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAllTables extends Migration
{
    public function up()
    {
        // ================================================================
        // 1. USUARIOS — Superclasse com atributos globais e credenciais
        // ================================================================
        $this->forge->addField([
            'id_usuario'       => ['type' => 'INT', 'auto_increment' => true],
            'nome'             => ['type' => 'VARCHAR', 'constraint' => 255],
            'email'            => ['type' => 'VARCHAR', 'constraint' => 255, 'unique' => true],
            'senha'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'telefone'         => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'data_cadastro'    => ['type' => 'DATETIME', 'null' => true],
            'reset_token'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'reset_expires_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_usuario', true);
        $this->forge->createTable('usuarios', true);

        // ================================================================
        // 2. CLIENTES — Subclasse de Usuario
        // ================================================================
        $this->forge->addField([
            'id_cliente'      => ['type' => 'INT', 'auto_increment' => true],
            'id_usuario'      => ['type' => 'INT'],
            'cpf'             => ['type' => 'VARCHAR', 'constraint' => 11, 'unique' => true],
            'data_nascimento' => ['type' => 'DATE', 'null' => true],
        ]);
        $this->forge->addKey('id_cliente', true);
        $this->forge->addForeignKey('id_usuario', 'usuarios', 'id_usuario', 'CASCADE', 'CASCADE');
        $this->forge->createTable('clientes', true);

        // ================================================================
        // 3. ARTESAOS — Subclasse de Usuario
        // ================================================================
        $this->forge->addField([
            'id_artesao' => ['type' => 'INT', 'auto_increment' => true],
            'id_usuario' => ['type' => 'INT'],
            'biografia'  => ['type' => 'TEXT', 'null' => true],
            'portfolio'  => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id_artesao', true);
        $this->forge->addForeignKey('id_usuario', 'usuarios', 'id_usuario', 'CASCADE', 'CASCADE');
        $this->forge->createTable('artesaos', true);

        // ================================================================
        // 4. FUNCIONARIOS — Subclasse de Usuario (admin)
        // ================================================================
        $this->forge->addField([
            'id_funcionario' => ['type' => 'INT', 'auto_increment' => true],
            'id_usuario'     => ['type' => 'INT'],
            'cargo'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'setor'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        ]);
        $this->forge->addKey('id_funcionario', true);
        $this->forge->addForeignKey('id_usuario', 'usuarios', 'id_usuario', 'CASCADE', 'CASCADE');
        $this->forge->createTable('funcionarios', true);

        // ================================================================
        // 5. ENDERECOS — Normalização 3FN, um cliente pode ter N endereços
        // ================================================================
        $this->forge->addField([
            'id_endereco' => ['type' => 'INT', 'auto_increment' => true],
            'id_cliente'  => ['type' => 'INT'],
            'logradouro'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'numero'      => ['type' => 'VARCHAR', 'constraint' => 20],
            'complemento' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'bairro'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'cidade'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'estado'      => ['type' => 'VARCHAR', 'constraint' => 2],
            'cep'         => ['type' => 'VARCHAR', 'constraint' => 9],
            'tipo'        => ['type' => 'ENUM', 'constraint' => ['principal', 'alternativo'], 'default' => 'principal'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_endereco', true);
        $this->forge->addForeignKey('id_cliente', 'clientes', 'id_cliente', 'CASCADE', 'CASCADE');
        $this->forge->createTable('enderecos', true);

        // ================================================================
        // 6. CATEGORIAS
        // ================================================================
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'nome'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug'       => ['type' => 'VARCHAR', 'constraint' => 255, 'unique' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('categorias', true);

        // ================================================================
        // 7. PRODUTOS — FK para artesaos.id_artesao
        // ================================================================
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'auto_increment' => true],
            'artesao_id'      => ['type' => 'INT'],
            'categoria_id'    => ['type' => 'INT'],
            'nome'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'descricao'       => ['type' => 'TEXT', 'null' => true],
            'preco'           => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'prazo_confeccao' => ['type' => 'INT', 'default' => 1],
            'estoque'         => ['type' => 'INT', 'default' => 0],
            'tipo'            => ['type' => 'ENUM', 'constraint' => ['pronta_entrega', 'sob_encomenda'], 'default' => 'pronta_entrega'],
            'imagem_path'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('artesao_id', 'artesaos', 'id_artesao', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('categoria_id', 'categorias', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('produtos', true);

        // ================================================================
        // 8. PEDIDOS — FKs com ON DELETE RESTRICT para preservar histórico
        // ================================================================
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'auto_increment' => true],
            'id_cliente'      => ['type' => 'INT'],
            'id_artesao'      => ['type' => 'INT'],
            'id_endereco'     => ['type' => 'INT'],
            'codigo_rastreio' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'valor_total'     => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'status_entrega'  => [
                'type'       => 'ENUM',
                'constraint' => ['em_producao', 'aguardando_coleta', 'enviado', 'entregue', 'cancelado'],
                'default'    => 'em_producao',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_cliente',  'clientes',  'id_cliente',  'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_artesao',  'artesaos',  'id_artesao',  'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_endereco', 'enderecos', 'id_endereco', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('pedidos', true);

        // ================================================================
        // 9. PAGAMENTOS — UC08, separado de pedidos, ON DELETE RESTRICT
        // ================================================================
        $this->forge->addField([
            'id_pagamento' => ['type' => 'INT', 'auto_increment' => true],
            'id_pedido'    => ['type' => 'INT'],
            'metodo'       => ['type' => 'ENUM', 'constraint' => ['pix', 'cartao'], 'default' => 'pix'],
            'status'       => [
                'type'       => 'ENUM',
                'constraint' => ['pendente', 'aprovado', 'recusado', 'estornado'],
                'default'    => 'pendente',
            ],
            'valor'       => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'gateway_ref' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_pagamento', true);
        $this->forge->addForeignKey('id_pedido', 'pedidos', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('pagamentos', true);

        // ================================================================
        // 10. ITENS_PEDIDO
        // ================================================================
        $this->forge->addField([
            'id'                       => ['type' => 'INT', 'auto_increment' => true],
            'pedido_id'                => ['type' => 'INT'],
            'produto_id'               => ['type' => 'INT', 'null' => true],
            'nome_produto_historico'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'preco_unitario_historico' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'quantidade'               => ['type' => 'INT', 'default' => 1],
            'subtotal'                 => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'created_at'               => ['type' => 'DATETIME', 'null' => true],
            'updated_at'               => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pedido_id',  'pedidos',  'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('produto_id', 'produtos', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('itens_pedido', true);

        // ================================================================
        // 11. ESTORNOS — UC09
        // ================================================================
        $this->forge->addField([
            'id_estorno'   => ['type' => 'INT', 'auto_increment' => true],
            'id_pagamento' => ['type' => 'INT'],
            'valor'        => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'motivo'       => ['type' => 'TEXT'],
            'data_estorno' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id_estorno', true);
        $this->forge->addForeignKey('id_pagamento', 'pagamentos', 'id_pagamento', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('estornos', true);

        // ================================================================
        // 12. LOGIN_TENTATIVAS — RNF06 (bloqueio após 5 tentativas)
        // ================================================================
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'auto_increment' => true],
            'email'        => ['type' => 'VARCHAR', 'constraint' => 255, 'unique' => true],
            'tentativas'   => ['type' => 'INT', 'default' => 0],
            'bloqueado_ate' => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('login_tentativas', true);
    }

    public function down()
    {
        // Remove na ordem inversa respeitando as FKs
        $this->forge->dropTable('login_tentativas', true);
        $this->forge->dropTable('estornos',         true);
        $this->forge->dropTable('itens_pedido',     true);
        $this->forge->dropTable('pagamentos',       true);
        $this->forge->dropTable('pedidos',          true);
        $this->forge->dropTable('produtos',         true);
        $this->forge->dropTable('categorias',       true);
        $this->forge->dropTable('enderecos',        true);
        $this->forge->dropTable('funcionarios',     true);
        $this->forge->dropTable('artesaos',         true);
        $this->forge->dropTable('clientes',         true);
        $this->forge->dropTable('usuarios',         true);
    }
}
