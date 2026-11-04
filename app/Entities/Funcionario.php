<?php

declare(strict_types=1);

namespace App\Entities;

/**
 * Entidade Funcionario — Subclasse de Usuario (Class Table Heritage).
 * Representa administradores e funcionários do sistema.
 */
class Funcionario extends Usuario
{
    public function __construct(
        int    $id_usuario,
        string $nome,
        string $email,
        string $telefone,
        string $data_cadastro,
        public readonly int     $id_funcionario,
        public readonly ?string $cargo = null,
        public readonly ?string $setor = null,
    ) {
        parent::__construct($id_usuario, $nome, $email, $telefone, $data_cadastro);
    }

    public function getTipo(): string
    {
        return 'funcionario';
    }

    public function isAdmin(): bool
    {
        return true;
    }
}
