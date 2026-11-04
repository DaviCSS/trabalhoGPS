<?php

declare(strict_types=1);

namespace App\Services;

class PixService
{
    // Aceita os formatos de chave usados pelo Banco Central
    public function validarChave(string $chave): bool
    {
        $chave = trim($chave);

        return (bool) filter_var($chave, FILTER_VALIDATE_EMAIL)
            || (bool) preg_match('/^\d{11}$|^\d{14}$/', $chave)
            || (bool) preg_match('/^\+55\d{10,11}$/', $chave)
            || (bool) preg_match('/^[a-f0-9]{8}(-[a-f0-9]{4}){3}-[a-f0-9]{12}$/i', $chave);
    }

    // Monta um campo no formato ID + tamanho + conteúdo
    private function campo(string $id, string $valor): string
    {
        return $id . str_pad((string) strlen($valor), 2, '0', STR_PAD_LEFT) . $valor;
    }

    private function textoPix(string $texto, int $tamanho): string
    {
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);
        if ($ascii === false) {
            $ascii = $texto;
        }

        $ascii = strtoupper(preg_replace('/[^a-zA-Z0-9 ]/', '', $ascii));
        return substr(trim($ascii), 0, $tamanho);
    }

    // Gera o BR Code (Pix estático com valor e identificador do pedido)
    public function gerarCodigo(string $chave, string $nome, string $cidade, float $valor, int $pedidoId): string
    {
        if (!$this->validarChave($chave) || $valor <= 0 || trim($cidade) === '') {
            throw new \RuntimeException('Configure uma chave Pix e uma cidade válidas antes de receber pagamentos.');
        }

        $conta  = $this->campo('00', 'br.gov.bcb.pix') . $this->campo('01', trim($chave));
        $txid   = 'CROCH' . $pedidoId;
        $codigo = $this->campo('00', '01')
            . $this->campo('26', $conta)
            . $this->campo('52', '0000')
            . $this->campo('53', '986')
            . $this->campo('54', number_format($valor, 2, '.', ''))
            . $this->campo('58', 'BR')
            . $this->campo('59', $this->textoPix($nome, 25))
            . $this->campo('60', $this->textoPix($cidade, 15))
            . $this->campo('62', $this->campo('05', $txid))
            . '6304';

        // CRC16/CCITT-FALSE exigido no BR Code
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($codigo); $i++) {
            $crc ^= ord($codigo[$i]) << 8;
            for ($j = 0; $j < 8; $j++) {
                $crc = ($crc & 0x8000) ? (($crc << 1) ^ 0x1021) : ($crc << 1);
                $crc &= 0xFFFF;
            }
        }

        return $codigo . strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }
}
