<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PedidoModel;
use App\Models\VendaAvulsaModel;

class FinanceiroService
{
    // Retorna o período de consulta e o texto exibido no relatório
    private function intervalo(string $periodo, string $mes): array
    {
        $hoje = new \DateTimeImmutable('today');

        if ($periodo === 'total') {
            return [null, null, 'Todas as vendas'];
        }

        if ($periodo === 'semana') {
            $inicio = $hoje->modify('monday this week');
            return [$inicio, $inicio->modify('+7 days'), 'Esta semana'];
        }

        if ($periodo === 'mes_anterior') {
            $inicio = $hoje->modify('first day of last month');
            return [$inicio, $inicio->modify('+1 month'), 'Mês passado'];
        }

        if ($periodo === 'mes' && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $mes)) {
            $inicio = \DateTimeImmutable::createFromFormat('!Y-m-d', $mes . '-01');
            if ($inicio !== false) {
                return [$inicio, $inicio->modify('+1 month'), $inicio->format('m/Y')];
            }
        }

        $inicio = $hoje->modify('first day of this month');
        return [$inicio, $inicio->modify('+1 month'), 'Este mês'];
    }

    // Junta as vendas cadastradas e os pedidos do site
    public function consultar(int $id_artesao, string $periodo = 'mes_atual', string $mes = ''): array
    {
        [$inicio, $fim, $rotulo] = $this->intervalo($periodo, $mes);
        $registros = [];
        $totalRecebido = 0.0;
        $totalVendas = 0;
        $pendentes = 0;
        $entregues = 0;

        foreach ((new PedidoModel())->listarPorArtesa($id_artesao) as $pedido) {
            $data = new \DateTimeImmutable($pedido->created_at);
            if (($inicio && $data < $inicio) || ($fim && $data >= $fim)) {
                continue;
            }

            $recebido = ($pedido->status_pagamento === 'aprovado' && $pedido->status_entrega !== 'cancelado');
            $cancelado = ($pedido->status_entrega === 'cancelado');

            if ($recebido) {
                $totalRecebido += (float) $pedido->valor_total;
                $totalVendas++;
            } elseif (!$cancelado && $pedido->status_pagamento === 'pendente') {
                $pendentes++;
            }

            if ($pedido->status_entrega === 'entregue') {
                $entregues++;
            }

            $registros[] = [
                'id'        => $pedido->id,
                'produto'   => 'Pedido #' . $pedido->id,
                'cliente'   => $pedido->cliente_nome ?? 'Cliente',
                'data'      => $pedido->created_at,
                'valor'     => (float) $pedido->valor_total,
                'situacao'  => $cancelado ? 'Cancelado' : ($recebido ? 'Recebido' : 'Pendente'),
                'recebido'  => $recebido,
                'tipo'      => 'Pedido',
            ];
        }

        $vendas = (new VendaAvulsaModel())->where('id_artesao', $id_artesao)->findAll();
        foreach ($vendas as $venda) {
            $data = new \DateTimeImmutable($venda->created_at);
            if (($inicio && $data < $inicio) || ($fim && $data >= $fim)) {
                continue;
            }

            $totalRecebido += (float) $venda->valor;
            $totalVendas++;

            $registros[] = [
                'id'       => $venda->id,
                'produto'  => $venda->produto,
                'cliente'  => 'Venda registrada',
                'data'     => $venda->created_at,
                'valor'    => (float) $venda->valor,
                'situacao' => 'Recebido',
                'recebido' => true,
                'tipo'     => 'Venda avulsa',
            ];
        }

        usort($registros, static fn (array $a, array $b): int => strcmp($b['data'], $a['data']));

        return [
            'periodo'         => $rotulo,
            'registros'       => $registros,
            'totalRecebido'   => round($totalRecebido, 2),
            'totalVendas'     => $totalVendas,
            'pendentes'       => $pendentes,
            'entregues'       => $entregues,
        ];
    }
}
