<?php

declare(strict_types=1);

namespace App\Services;

class SuporteWhatsAppService
{
    // Cria um link de conversa com mensagem pronta
    public function gerarLink(string $telefone): ?string
    {
        $numero = preg_replace('/[^0-9]/', '', $telefone);

        if (strlen($numero) === 10 || strlen($numero) === 11) {
            $numero = '55' . $numero;
        }

        if (!preg_match('/^55[1-9][0-9]{9,10}$/', $numero)) {
            return null;
        }

        $mensagem = 'Olá! Sou artesã da Crochettei e preciso de ajuda para usar a plataforma.';
        return 'https://wa.me/' . $numero . '?text=' . rawurlencode($mensagem);
    }
}
