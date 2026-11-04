<?php

declare(strict_types=1);

namespace App\Entities;

/**
 * Entidade abstrata Usuario — Superclasse do modelo de herança (Class Table Heritage).
 * Contém apenas os atributos globais e de credenciais comuns a todos os perfis.
 */
abstract class Usuario
{
    public function __construct(
        public readonly int    $id_usuario,
        public readonly string $nome,
        public readonly string $email,
        public readonly string $telefone,
        public readonly string $data_cadastro,
    ) {}

    /**
     * Retorna o tipo de perfil do usuário ('cliente', 'artesao', 'funcionario').
     */
    abstract public function getTipo(): string;
}
