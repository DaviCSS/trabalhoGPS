<?php

namespace Tests\Unit;

use App\Services\SuporteWhatsAppService;
use PHPUnit\Framework\TestCase;

class SuporteWhatsAppServiceTest extends TestCase
{
    public function testLinkComMensagem(): void
    {
        $servico = new SuporteWhatsAppService();
        $link = $servico->gerarLink('(27) 99999-1234');

        $this->assertStringStartsWith('https://wa.me/5527999991234?text=', $link);
        $this->assertStringContainsString('Crochettei', rawurldecode($link));
    }

    public function testLinkComCodigoDoBrasil(): void
    {
        $servico = new SuporteWhatsAppService();
        $this->assertStringStartsWith('https://wa.me/5527999991234', $servico->gerarLink('5527999991234'));
    }

    public function testNumeroInvalido(): void
    {
        $servico = new SuporteWhatsAppService();
        $this->assertNull($servico->gerarLink(''));
        $this->assertNull($servico->gerarLink('123'));
        $this->assertNull($servico->gerarLink('texto'));
    }
}
