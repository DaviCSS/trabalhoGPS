<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\PedidoModel;

/**
 * UC11 — Rastrear Encomenda
 * Retorna status logístico detalhado baseado no status_entrega e código de rastreio do pedido.
 */
class RastrearEncomendaUseCase
{
    public function __construct(
        private readonly PedidoModel $pedidoModel
    ) {}

    /**
     * Rastreia pelo ID do pedido.
     * @return array com status, descricao, eventos mock e codigo_rastreio
     */
    public function rastrearPorId(int $id_pedido): array
    {
        $pedido = $this->pedidoModel->find($id_pedido);

        if ($pedido === null) {
            throw new \RuntimeException('Pedido não encontrado. (UC11)');
        }

        return $this->montarRastreio($pedido);
    }

    /**
     * Rastreia pelo código de rastreio.
     */
    public function rastrearPorCodigo(string $codigo): array
    {
        $pedido = $this->pedidoModel->buscarPorRastreio($codigo);

        if ($pedido === null) {
            throw new \RuntimeException("Código de rastreio '{$codigo}' não encontrado. (UC11)");
        }

        return $this->montarRastreio($pedido);
    }

    private function montarRastreio(object $pedido): array
    {
        $status   = $pedido->status_entrega ?? 'em_producao';
        $rastreio = $pedido->codigo_rastreio ?? null;

        // Mapa de status → descrição e ícone
        $statusInfo = [
            'em_producao'       => ['desc' => 'Sua peça está sendo confeccionada com carinho pela artesã.',   'icone' => 'sewing'],
            'aguardando_coleta' => ['desc' => 'Produto pronto! Aguardando coleta pela transportadora.',        'icone' => 'inventory_2'],
            'enviado'           => ['desc' => 'Pedido enviado! Em trânsito pelos Correios.',                   'icone' => 'local_shipping'],
            'entregue'          => ['desc' => 'Entregue com sucesso! Esperamos que você ame o produto. 🧶',    'icone' => 'check_circle'],
            'cancelado'         => ['desc' => 'Pedido cancelado. Entre em contato para mais informações.',      'icone' => 'cancel'],
        ];

        // Eventos de rastreio simulados (mock progressivo por status)
        $todosEventos = [
            ['status' => 'em_producao',       'data' => date('d/m/Y H:i', strtotime($pedido->created_at ?? 'now')),    'evento' => 'Pedido recebido e em produção pela artesã'],
            ['status' => 'aguardando_coleta', 'data' => date('d/m/Y H:i', strtotime($pedido->updated_at ?? '+2 days')), 'evento' => 'Produto finalizado — aguardando coleta'],
            ['status' => 'enviado',            'data' => date('d/m/Y H:i', strtotime($pedido->updated_at ?? '+3 days')), 'evento' => 'Objeto postado nos Correios' . ($rastreio ? " (Cód: {$rastreio})" : '')],
            ['status' => 'entregue',           'data' => date('d/m/Y H:i', strtotime($pedido->updated_at ?? '+7 days')), 'evento' => 'Objeto entregue ao destinatário'],
        ];

        // Filtra apenas os eventos até o status atual
        $ordem   = ['em_producao', 'aguardando_coleta', 'enviado', 'entregue', 'cancelado'];
        $idxAtual = array_search($status, $ordem, true) ?? 0;
        $eventos  = array_filter(
            $todosEventos,
            fn($e) => array_search($e['status'], $ordem, true) <= $idxAtual
        );

        return [
            'pedido_id'       => $pedido->id,
            'status'          => $status,
            'descricao'       => $statusInfo[$status]['desc']  ?? 'Status desconhecido.',
            'icone'           => $statusInfo[$status]['icone'] ?? 'help',
            'codigo_rastreio' => $rastreio,
            'eventos'         => array_values($eventos),
        ];
    }
}
