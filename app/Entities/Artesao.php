<?php

declare(strict_types=1);

namespace App\Entities;

/**
 * Entidade Artesao — Subclasse de Usuario (Class Table Heritage).
 * Possui biografia e portfólio.
 */
class Artesao extends Usuario
{
    public function __construct(
        int    $id_usuario,
        string $nome,
        string $email,
        string $telefone,
        string $data_cadastro,
        public readonly int     $id_artesao,
        public readonly ?string $biografia = null,
        public readonly ?string $portfolio  = null,
    ) {
        parent::__construct($id_usuario, $nome, $email, $telefone, $data_cadastro);
    }

    public function getTipo(): string
    {
        return 'artesao';
    }
}
