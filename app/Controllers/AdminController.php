<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\PedidoModel;
use App\Models\ProdutoModel;
use App\Models\PagamentoModel;
use App\UseCases\ProcessarTransacaoFinanceiraUseCase;
use App\UseCases\EmitirEstornoUseCase;
use App\Models\EstornoModel;

class AdminController extends BaseController
{
    // Puxa um resumão de todas as vendas e métricas do e-commerce para a administração
    public function dashboard()
    {
        $pedidoModel  = new PedidoModel();
        $produtoModel = new ProdutoModel();

        $totalProdutos = $produtoModel->where('estoque >', 0)->countAllResults();
        $metrics       = $pedidoModel->metricsGlobais();

        $totalFaturado = (float) ($metrics->total_faturado   ?? 0);
        $pedidosAtivos = (int)   ($metrics->pedidos_pendentes ?? 0);

        // Soma dos pagamentos aprovados
        $db             = \Config\Database::connect();
        $saldoAReceber  = (float) ($db->table('pagamentos')
            ->selectSum('valor')
            ->where('status', 'aprovado')
            ->get()->getRow()->valor ?? 0);

        $pedidos = $pedidoModel->listarTodos();

        return view('admin/dashboard', [
            'totalProdutos' => $totalProdutos,
            'totalFaturado' => $totalFaturado,
            'pedidosAtivos' => $pedidosAtivos,
            'saldoAReceber' => $saldoAReceber,
            'pedidos'       => $pedidos,
        ]);
    }

    // UC08 — Confirma que o dinheiro caiu via gateway mock
    public function aprovarPagamento(int $id)
    {
        $pedidoModel   = new PedidoModel();
        $pagamentoModel = new PagamentoModel();

        $pagamento = $pagamentoModel->buscarPorPedido($id);

        if (!$pagamento) {
            session()->setFlashdata('erro', "Nenhum pagamento encontrado para o pedido #{$id}.");
            return redirect()->to('/admin/dashboard');
        }

        $useCase   = new ProcessarTransacaoFinanceiraUseCase($pagamentoModel, $pedidoModel);
        $resultado = $useCase->processar(
            $id,
            (float) $pagamento->valor,
            $pagamento->metodo,
            '' // CPF vazio → aprovação garantida no mock
        );

        if ($resultado['aprovado']) {
            session()->setFlashdata('sucesso', "Pagamento do pedido #{$id} aprovado com sucesso! A artesã será notificada.");
        } else {
            session()->setFlashdata('erro', "Falha ao aprovar o pagamento do pedido #{$id}.");
        }

        return redirect()->to('/admin/dashboard');
    }

    // UC09 — Emite estorno de um pagamento aprovado
    public function estornar(int $id_pagamento)
    {
        $motivo = trim($this->request->getPost('motivo') ?? 'Estorno solicitado pelo administrador.');
        $valor  = (float) $this->request->getPost('valor');

        if ($valor <= 0) {
            session()->setFlashdata('erro', 'Informe um valor válido para o estorno.');
            return redirect()->to('/admin/dashboard');
        }

        try {
            $useCase = new EmitirEstornoUseCase(new EstornoModel(), new PagamentoModel());
            $useCase->executar($id_pagamento, $valor, $motivo);
            session()->setFlashdata('sucesso', "Estorno de R$ " . number_format($valor, 2, ',', '.') . " registrado com sucesso.");
        } catch (\RuntimeException $e) {
            session()->setFlashdata('erro', $e->getMessage());
        }

        return redirect()->to('/admin/dashboard');
    }

    // Cancela o pedido à força em caso de problemas
    public function forcarCancelamento(int $id)
    {
        $pedidoModel = new PedidoModel();
        if ($pedidoModel->update($id, ['status_entrega' => 'cancelado'])) {
            session()->setFlashdata('sucesso', "Pedido #{$id} cancelado com sucesso. A ação já refletiu no painel da artesã e do cliente.");
        } else {
            session()->setFlashdata('erro', 'Erro ao cancelar o pedido.');
        }
        return redirect()->to('/admin/dashboard');
    }
}
