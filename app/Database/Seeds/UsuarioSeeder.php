<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $db          = \Config\Database::connect();
        $senhaPadrao = password_hash('Senha123', PASSWORD_BCRYPT);
        $agora       = date('Y-m-d H:i:s');

        // ============================================================
        // ARTESÃS — inserir em usuarios + artesaos
        // ============================================================
        $artesaas = [
            [
                'usuario' => [
                    'nome'          => 'Maria das Graças',
                    'email'         => 'maria.artesa@crochettei.com',
                    'senha'         => $senhaPadrao,
                    'telefone'      => '27999991111',
                    'data_cadastro' => $agora,
                ],
                'artesao' => [
                    'biografia' => 'Artesã com mais de 10 anos de experiência em crochê artesanal.',
                    'portfolio'  => null,
                ],
            ],
            [
                'usuario' => [
                    'nome'          => 'Lourdes Oliveira',
                    'email'         => 'lourdes.oliveira@email.com',
                    'senha'         => $senhaPadrao,
                    'telefone'      => '27988882222',
                    'data_cadastro' => $agora,
                ],
                'artesao' => [
                    'biografia' => 'Especialista em roupas de crochê e acessórios de moda.',
                    'portfolio'  => null,
                ],
            ],
            [
                'usuario' => [
                    'nome'          => 'Fátima Silva',
                    'email'         => 'fatima.silva@email.com',
                    'senha'         => $senhaPadrao,
                    'telefone'      => '27977773333',
                    'data_cadastro' => $agora,
                ],
                'artesao' => [
                    'biografia' => 'Criadora dos famosos polvos amigurumi solidários para UTI neonatal.',
                    'portfolio'  => null,
                ],
            ],
        ];

        foreach ($artesaas as $dados) {
            $existe = $db->table('usuarios')->where('email', $dados['usuario']['email'])->get()->getRow();
            if ($existe) {
                continue;
            }

            $db->table('usuarios')->insert($dados['usuario']);
            $id_usuario = $db->insertID();

            $db->table('artesaos')->insert([
                'id_usuario' => $id_usuario,
                'biografia'  => $dados['artesao']['biografia'],
                'portfolio'  => $dados['artesao']['portfolio'],
            ]);
        }

        // ============================================================
        // CLIENTES — inserir em usuarios + clientes + enderecos
        // ============================================================
        $clientes = [
            [
                'usuario' => [
                    'nome'          => 'Fernanda Costa',
                    'email'         => 'fernanda.costa@email.com',
                    'senha'         => $senhaPadrao,
                    'telefone'      => '27966664444',
                    'data_cadastro' => $agora,
                ],
                'cliente' => [
                    'cpf'             => '44444444444',
                    'data_nascimento' => '1990-05-15',
                ],
                'endereco' => [
                    'logradouro'  => 'Rua dos Passarinhos',
                    'numero'      => '55',
                    'complemento' => null,
                    'bairro'      => 'Jardim da Penha',
                    'cidade'      => 'Vitória',
                    'estado'      => 'ES',
                    'cep'         => '29060050',
                    'tipo'        => 'principal',
                ],
            ],
            [
                'usuario' => [
                    'nome'          => 'Ana Clara Marques',
                    'email'         => 'ana.compras@exemplo.com',
                    'senha'         => $senhaPadrao,
                    'telefone'      => '27955555555',
                    'data_cadastro' => $agora,
                ],
                'cliente' => [
                    'cpf'             => '55555555555',
                    'data_nascimento' => '1995-11-22',
                ],
                'endereco' => [
                    'logradouro'  => 'Av. Vitória',
                    'numero'      => '1200',
                    'complemento' => 'Apto 302',
                    'bairro'      => 'Centro',
                    'cidade'      => 'Vitória',
                    'estado'      => 'ES',
                    'cep'         => '29010010',
                    'tipo'        => 'principal',
                ],
            ],
        ];

        foreach ($clientes as $dados) {
            $existe = $db->table('usuarios')->where('email', $dados['usuario']['email'])->get()->getRow();
            if ($existe) {
                continue;
            }

            $db->table('usuarios')->insert($dados['usuario']);
            $id_usuario = $db->insertID();

            $db->table('clientes')->insert([
                'id_usuario'      => $id_usuario,
                'cpf'             => $dados['cliente']['cpf'],
                'data_nascimento' => $dados['cliente']['data_nascimento'],
            ]);
            $id_cliente = $db->insertID();

            $db->table('enderecos')->insert(array_merge($dados['endereco'], [
                'id_cliente'  => $id_cliente,
                'created_at'  => $agora,
                'updated_at'  => $agora,
            ]));
        }

        // ============================================================
        // FUNCIONÁRIO / ADMIN — inserir em usuarios + funcionarios
        // ============================================================
        $existe = $db->table('usuarios')->where('email', 'admin@crochettei.com')->get()->getRow();
        if (!$existe) {
            $db->table('usuarios')->insert([
                'nome'          => 'Administrador do Sistema',
                'email'         => 'admin@crochettei.com',
                'senha'         => $senhaPadrao,
                'telefone'      => '27900000000',
                'data_cadastro' => $agora,
            ]);
            $id_usuario = $db->insertID();

            $db->table('funcionarios')->insert([
                'id_usuario' => $id_usuario,
                'cargo'      => 'Administrador',
                'setor'      => 'TI',
            ]);
        }
    }
}
