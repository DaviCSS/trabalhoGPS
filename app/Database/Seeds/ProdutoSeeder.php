<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    public function run()
    {
        $db           = \Config\Database::connect();
        $categoriaModel = new \App\Models\CategoriaModel();

        // Busca artesãs via JOIN artesaos → usuarios
        $maria = $db->table('artesaos a')
            ->select('a.id_artesao')
            ->join('usuarios u', 'u.id_usuario = a.id_usuario')
            ->where('u.email', 'maria.artesa@crochettei.com')
            ->get()->getRow();

        $lurdes = $db->table('artesaos a')
            ->select('a.id_artesao')
            ->join('usuarios u', 'u.id_usuario = a.id_usuario')
            ->where('u.email', 'lourdes.oliveira@email.com')
            ->get()->getRow();

        $fatima = $db->table('artesaos a')
            ->select('a.id_artesao')
            ->join('usuarios u', 'u.id_usuario = a.id_usuario')
            ->where('u.email', 'fatima.silva@email.com')
            ->get()->getRow();

        $catAmigurumi = $categoriaModel->where('nome', 'Amigurumis')->first();
        $catDecoracao = $categoriaModel->where('nome', 'Decoração')->first();
        $catModa      = $categoriaModel->where('nome', 'Roupas')->first();

        if (!$maria || !$catAmigurumi) {
            echo "Aviso: Artesãs ou categorias não encontradas. Rode os seeders anteriores primeiro.\n";
            return;
        }

        $produtos = [
            [
                'artesao_id'      => $maria->id_artesao,
                'categoria_id'    => $catAmigurumi->id,
                'nome'            => 'Urso de Pelúcia Amigurumi',
                'descricao'       => 'Lindo ursinho feito à mão com fio 100% algodão e enchimento antialérgico. Ideal para presentear bebês e crianças.',
                'preco'           => 120.00,
                'prazo_confeccao' => 7,
                'estoque'         => 3,
                'tipo'            => 'pronta_entrega',
                'imagem_path'     => 'https://loremflickr.com/600/400/amigurumi',
            ],
            [
                'artesao_id'      => $maria->id_artesao,
                'categoria_id'    => $catDecoracao->id,
                'nome'            => 'Tapete de Barbante Cru',
                'descricao'       => 'Tapete rústico de crochê feito em barbante cru, tamanho 1,20m x 0,80m. Fica lindo na sala ou quarto.',
                'preco'           => 150.00,
                'prazo_confeccao' => 10,
                'estoque'         => 1,
                'tipo'            => 'pronta_entrega',
                'imagem_path'     => 'https://loremflickr.com/600/400/rug',
            ],
            [
                'artesao_id'      => $lurdes->id_artesao,
                'categoria_id'    => $catModa->id,
                'nome'            => 'Blusa de Frio em Crochê',
                'descricao'       => 'Blusa de lã quentinha e muito charmosa. Feita sob medida em até 15 dias.',
                'preco'           => 210.00,
                'prazo_confeccao' => 15,
                'estoque'         => 0,
                'tipo'            => 'sob_encomenda',
                'imagem_path'     => 'https://loremflickr.com/600/400/sweater,crochet',
            ],
            [
                'artesao_id'      => $lurdes->id_artesao,
                'categoria_id'    => $catDecoracao->id,
                'nome'            => 'Manta para Sofá Colorida',
                'descricao'       => 'Manta super colorida feita com sobras de lã. Estilo Boho para dar vida à sua sala de estar.',
                'preco'           => 180.00,
                'prazo_confeccao' => 20,
                'estoque'         => 2,
                'tipo'            => 'pronta_entrega',
                'imagem_path'     => 'https://loremflickr.com/600/400/blanket,crochet',
            ],
            [
                'artesao_id'      => $fatima->id_artesao,
                'categoria_id'    => $catAmigurumi->id,
                'nome'            => 'Polvo Amigurumi para Prematuros',
                'descricao'       => 'Polvo feito com pontos bem fechados, perfeito para transmitir segurança aos recém-nascidos.',
                'preco'           => 45.00,
                'prazo_confeccao' => 5,
                'estoque'         => 10,
                'tipo'            => 'pronta_entrega',
                'imagem_path'     => 'https://loremflickr.com/600/400/octopus,amigurumi',
            ],
            [
                'artesao_id'      => $fatima->id_artesao,
                'categoria_id'    => $catDecoracao->id,
                'nome'            => 'Sousplat Natalino (Jogo de 4)',
                'descricao'       => 'Deixe sua mesa de Natal deslumbrante com este jogo de sousplats vermelhos com fio dourado.',
                'preco'           => 100.00,
                'prazo_confeccao' => 8,
                'estoque'         => 5,
                'tipo'            => 'pronta_entrega',
                'imagem_path'     => 'https://loremflickr.com/600/400/tablemat,crochet',
            ],
        ];

        $produtoModel = new \App\Models\ProdutoModel();
        foreach ($produtos as $prod) {
            if (!$produtoModel->where('nome', $prod['nome'])->first()) {
                $produtoModel->insert($prod);
            }
        }
    }
}
