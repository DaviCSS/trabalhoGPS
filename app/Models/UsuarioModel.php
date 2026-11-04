<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table      = 'usuarios';
    protected $primaryKey = 'id_usuario';
    protected $returnType = 'object';

    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'nome',
        'email',
        'senha',
        'telefone',
        'data_cadastro',
        'reset_token',
        'reset_expires_at',
    ];

    // data_cadastro é o campo de criação
    protected $useTimestamps  = true;
    protected $createdField   = 'data_cadastro';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';

    protected $beforeInsert = ['hashSenha'];
    protected $beforeUpdate = ['hashSenhaSeAlterada'];

    // Criptografa a senha antes de salvar no banco de dados
    protected function hashSenha(array $data): array
    {
        if (isset($data['data']['senha'])) {
            $data['data']['senha'] = password_hash(
                $data['data']['senha'],
                PASSWORD_BCRYPT
            );
        }
        return $data;
    }

    protected function hashSenhaSeAlterada(array $data): array
    {
        if (!empty($data['data']['senha'])) {
            $data['data']['senha'] = password_hash(
                $data['data']['senha'],
                PASSWORD_BCRYPT
            );
        }
        return $data;
    }

    public function buscarPorEmail(string $email): ?object
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Autentica o usuário e retorna o objeto base da tabela usuarios.
     * A identificação do perfil (cliente/artesao/funcionario) é feita
     * em seguida pelo AutenticarUsuarioUseCase.
     */
    public function autenticar(string $email, string $senha): ?object
    {
        $usuario = $this->buscarPorEmail($email);

        if ($usuario === null) {
            return null;
        }

        if (!password_verify($senha, $usuario->senha)) {
            return null;
        }

        return $usuario;
    }

    /**
     * Identifica o tipo de perfil do usuário e retorna o registro da subclasse.
     * Retorna um objeto com ->tipo ('artesao'|'funcionario'|'cliente') e o ID específico.
     */
    public function buscarPerfilCompleto(int $id_usuario): ?object
    {
        // Verifica artesão
        $artesao = $this->db->table('artesaos')
            ->where('id_usuario', $id_usuario)
            ->get()->getRow();

        if ($artesao) {
            $artesao->tipo = 'artesao';
            return $artesao;
        }

        // Verifica funcionário/admin
        $funcionario = $this->db->table('funcionarios')
            ->where('id_usuario', $id_usuario)
            ->get()->getRow();

        if ($funcionario) {
            $funcionario->tipo = 'funcionario';
            return $funcionario;
        }

        // Padrão: cliente
        $cliente = $this->db->table('clientes')
            ->where('id_usuario', $id_usuario)
            ->get()->getRow();

        if ($cliente) {
            $cliente->tipo = 'cliente';
            return $cliente;
        }

        return null;
    }

    /**
     * Busca usuário por CPF (via tabela clientes).
     */
    public function buscarPorCpf(string $cpf): ?object
    {
        $resultado = $this->db->table('clientes cl')
            ->select('u.*, cl.cpf, cl.id_cliente, cl.data_nascimento')
            ->join('usuarios u', 'u.id_usuario = cl.id_usuario')
            ->where('cl.cpf', $cpf)
            ->where('u.deleted_at IS NULL')
            ->get()->getRow();

        return $resultado ?: null;
    }

    /**
     * Para o fluxo de reset de senha.
     */
    public function regrasPerfil(int $id_usuario): self
    {
        return $this;
    }
}
