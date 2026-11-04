<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdicionarModuloFinanceiro extends Migration
{
    public function up()
    {
        // Dados do Pix da artesã
        $this->forge->addColumn('artesaos', [
            'chave_pix'  => ['type' => 'VARCHAR', 'constraint' => 77, 'null' => true],
            'cidade_pix' => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => true],
        ]);

        // Guarda a cobrança original para cada pedido
        $this->forge->addColumn('pagamentos', [
            'pix_copia_cola' => ['type' => 'TEXT', 'null' => true],
        ]);

        // Vendas realizadas fora do checkout da plataforma
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'id_artesao' => ['type' => 'INT'],
            'produto'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'valor'      => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_artesao', 'artesaos', 'id_artesao', 'CASCADE', 'CASCADE');
        $this->forge->createTable('vendas_avulsas', true);
    }

    public function down()
    {
        $this->forge->dropTable('vendas_avulsas', true);
        $this->forge->dropColumn('pagamentos', 'pix_copia_cola');
        $this->forge->dropColumn('artesaos', ['chave_pix', 'cidade_pix']);
    }
}
