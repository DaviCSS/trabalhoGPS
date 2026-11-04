<?php

namespace Tests\Unit;

use App\Services\PixService;
use PHPUnit\Framework\TestCase;

class PixServiceTest extends TestCase
{
    public function testValidaChavesPix(): void
    {
        $pix = new PixService();
        $this->assertTrue($pix->validarChave('contato@exemplo.com.br'));
        $this->assertTrue($pix->validarChave('12345678901'));
        $this->assertTrue($pix->validarChave('12345678000199'));
        $this->assertTrue($pix->validarChave('+5527999999999'));
        $this->assertTrue($pix->validarChave('123e4567-e89b-12d3-a456-426614174000'));
        $this->assertFalse($pix->validarChave('chave errada'));
        $this->assertFalse($pix->validarChave('123.456.789-01'));
        $this->assertFalse($pix->validarChave('(27) 99999-1234'));
    }

    public function testGeraCobrancaComValorEIdentificador(): void
    {
        $pix = new PixService();
        $codigo = $pix->gerarCodigo('contato@exemplo.com.br', 'Maria Artesa', 'Serra', 35.50, 123);

        $this->assertStringContainsString('br.gov.bcb.pix', $codigo);
        $this->assertStringContainsString('540535.50', $codigo);
        $this->assertStringContainsString('CROCH123', $codigo);
        $this->assertMatchesRegularExpression('/6304[0-9A-F]{4}$/', $codigo);
    }
}
