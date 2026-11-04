<?php

declare(strict_types=1);

namespace App\UseCases;

/**
 * UC07 — Calcular Frete Comercial (Mock por região de CEP)
 *
 * Tabela de frete simulada por faixa de CEP:
 *   CEP 29xxx (ES)             → R$ 15,00
 *   CEP 21xxx–23xxx (RJ)       → R$ 25,00
 *   CEP 01xxx–09xxx (SP cap.)  → R$ 30,00
 *   CEP 40xxx–48xxx (BA)       → R$ 35,00
 *   Demais regiões             → R$ 45,00
 */
class CalcularFreteComercialUseCase
{
    /**
     * @param string $cep    CEP de destino (apenas dígitos)
     * @param float  $total  Valor total do carrinho
     * @return array ['valor_frete' => float, 'prazo_dias' => int, 'regiao' => string]
     */
    public function calcular(string $cep, float $total): array
    {
        $cep    = preg_replace('/\D/', '', $cep);
        $prefix = (int) substr($cep, 0, 2);

        [$valorFrete, $prazo, $regiao] = match (true) {
            $prefix === 29                          => [15.00, 2,  'Espírito Santo'],
            $prefix >= 20 && $prefix <= 28          => [25.00, 3,  'Rio de Janeiro'],
            $prefix >= 1  && $prefix <= 9           => [30.00, 4,  'São Paulo (Capital)'],
            $prefix >= 10 && $prefix <= 19          => [32.00, 5,  'São Paulo (Interior)'],
            $prefix >= 30 && $prefix <= 39          => [35.00, 5,  'Minas Gerais'],
            $prefix >= 40 && $prefix <= 48          => [35.00, 6,  'Bahia'],
            $prefix >= 49 && $prefix <= 53          => [38.00, 6,  'Sergipe/Alagoas'],
            $prefix >= 54 && $prefix <= 56          => [40.00, 7,  'Pernambuco'],
            $prefix >= 57 && $prefix <= 65          => [42.00, 8,  'Nordeste'],
            $prefix >= 66 && $prefix <= 68          => [45.00, 9,  'Pará'],
            $prefix >= 69                           => [48.00, 10, 'Norte/AM'],
            default                                 => [45.00, 8,  'Demais regiões'],
        };

        // Frete grátis para compras acima de R$ 500
        if ($total >= 500.00) {
            $valorFrete = 0.00;
            $regiao    .= ' (Frete Grátis — acima de R$ 500)';
        }

        return [
            'valor_frete' => $valorFrete,
            'prazo_dias'  => $prazo,
            'regiao'      => $regiao,
            'total_com_frete' => round($total + $valorFrete, 2),
        ];
    }
}
