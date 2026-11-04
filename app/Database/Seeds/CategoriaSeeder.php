<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\CategoriaModel;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        $model = new CategoriaModel();

        // Limpa a tabela antes de popular (opcional, evita duplicações se rodar várias vezes)
        // Opcional: a limpeza total será tratada no fluxo se for necessário, mas para seed de dev, apenas insert.

        $categorias = [
            [
                'nome' => 'Roupas',
                'slug' => 'roupas'
            ],
            [
                'nome' => 'Decoração',
                'slug' => 'decoracao'
            ],
            [
                'nome' => 'Amigurumis',
                'slug' => 'amigurumis'
            ],
            [
                'nome' => 'Bolsas',
                'slug' => 'bolsas'
            ],
            [
                'nome' => 'Acessórios',
                'slug' => 'acessorios'
            ]
        ];

        foreach ($categorias as $cat) {
            // Verifica se a categoria já existe para evitar duplicação em múltiplas execuções
            if (!$model->where('nome', $cat['nome'])->first()) {
                $model->insert($cat);
            }
        }
    }
}
