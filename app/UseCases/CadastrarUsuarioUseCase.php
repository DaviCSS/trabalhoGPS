<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\UsuarioModel;
use App\Models\ClienteModel;
use App\Models\ArtesaoModel;
use App\Models\EnderecoModel;

/**
 * UC02/UC03 — Cadastrar Usuário (Cliente ou Artesã)
 * Realiza inserção atômica em múltiplas tabelas (Class Table Heritage + 3FN).
 */
class CadastrarUsuarioUseCase
{
    public function __construct(
        private readonly UsuarioModel  $usuarioModel,
        private readonly ClienteModel  $clienteModel  = new ClienteModel(),
        private readonly ArtesaoModel  $artesaoModel  = new ArtesaoModel(),
        private readonly EnderecoModel $enderecoModel = new EnderecoModel()
    ) {}

    /**
     * @param array $dados Campos do formulário (nome, email, senha, telefone, cpf, rua, numero...)
     * @param bool  $isArtesa Define se o perfil é Artesã ou Cliente
     */
    public function executar(array $dados, bool $isArtesa = false): int
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Inserir na tabela usuarios (superclasse)
            $dadosUsuario = [
                'nome'     => trim($dados['nome'] ?? ''),
                'email'    => strtolower(trim($dados['email'] ?? '')),
                'senha'    => $dados['senha'] ?? '',
                'telefone' => trim($dados['telefone'] ?? ''),
            ];

            // Verifica e-mail único
            if ($this->usuarioModel->buscarPorEmail($dadosUsuario['email'])) {
                throw new \RuntimeException('Este e-mail já está cadastrado no sistema.');
            }

            $id_usuario = $this->usuarioModel->insert($dadosUsuario, true);

            if ($id_usuario === false || !$id_usuario) {
                $erros = $this->usuarioModel->errors();
                throw new \RuntimeException(implode(' | ', $erros));
            }

            $id_usuario = (int) $id_usuario;

            if ($isArtesa) {
                // 2a. Inserir na tabela artesaos
                $db->table('artesaos')->insert([
                    'id_usuario' => $id_usuario,
                    'biografia'  => null,
                    'portfolio'  => null,
                ]);
            } else {
                // 2b. Inserir na tabela clientes
                $cpf = preg_replace('/\D/', '', $dados['cpf'] ?? '');

                // Verifica CPF único
                $cpfExiste = $db->table('clientes')->where('cpf', $cpf)->get()->getRow();
                if ($cpfExiste) {
                    throw new \RuntimeException('Este CPF já está cadastrado no sistema.');
                }

                $db->table('clientes')->insert([
                    'id_usuario'      => $id_usuario,
                    'cpf'             => $cpf,
                    'data_nascimento' => null,
                ]);
                $id_cliente = $db->insertID();

                // 3. Inserir endereço na tabela enderecos (3FN)
                $agora = date('Y-m-d H:i:s');
                $db->table('enderecos')->insert([
                    'id_cliente'  => $id_cliente,
                    'logradouro'  => trim($dados['rua']         ?? ''),
                    'numero'      => trim($dados['numero']       ?? ''),
                    'complemento' => trim($dados['complemento']  ?? '') ?: null,
                    'bairro'      => trim($dados['bairro']       ?? ''),
                    'cidade'      => trim($dados['cidade']       ?? ''),
                    'estado'      => trim($dados['estado']       ?? ''),
                    'cep'         => preg_replace('/\D/', '', $dados['cep'] ?? ''),
                    'tipo'        => 'principal',
                    'created_at'  => $agora,
                    'updated_at'  => $agora,
                ]);
            }

        } catch (\Throwable $e) {
            $db->transRollback();
            throw new \RuntimeException($e->getMessage());
        }

        $db->transComplete();

        return $id_usuario;
    }
}
