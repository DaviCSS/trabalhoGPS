<?php

declare(strict_types=1);

namespace App\Entities;

/**
 * Entidade Cliente — Subclasse de Usuario (Class Table Heritage).
 * Possui CPF e pode ter múltiplos endereços normalizados.
 */
class Cliente extends Usuario
{
    /** @param object[] $enderecos */
    public function __construct(
        int    $id_usuario,
        string $nome,
        string $email,
        string $telefone,
        string $data_cadastro,
        public readonly int     $id_cliente,
        public readonly string  $cpf,
        public readonly ?string $data_nascimento = null,
        public readonly array   $enderecos = [],
    ) {
        parent::__construct($id_usuario, $nome, $email, $telefone, $data_cadastro);
    }

    public function getTipo(): string
    {
        return 'cliente';
    }

    /**
     * Retorna o endereço principal do cliente ou null se não houver.
     */
    public function getEnderecoPrincipal(): ?object
    {
        foreach ($this->enderecos as $end) {
            if (($end->tipo ?? '') === 'principal') {
                return $end;
            }
        }
        return $this->enderecos[0] ?? null;
    }

    /**
     * Retorna o endereço formatado como texto (para exibição e compatibilidade).
     */
    public function getEnderecoFormatado(): string
    {
        $end = $this->getEnderecoPrincipal();
        if (!$end) {
            return '';
        }

        $partes = array_filter([
            $end->logradouro ?? '',
            isset($end->numero) ? 'nº ' . $end->numero : '',
            $end->complemento ?? '',
            $end->bairro ?? '',
            $end->cidade ?? '',
            isset($end->estado) ? '- ' . $end->estado : '',
            isset($end->cep) ? 'CEP: ' . $end->cep : '',
        ]);

        return implode(', ', $partes);
    }
}
