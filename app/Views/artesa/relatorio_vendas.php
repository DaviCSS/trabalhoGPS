<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Acompanhe suas vendas no Crochettei. Veja o total vendido e os últimos pedidos de forma simples.">
	<title>Crochettei — Relatório de Vendas</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

<link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/relatorio.css') ?>">
</head>

<body class="paginaDashboard">

<header class="navBar">
		<div class="logo">
			<h1>Crochettei</h1>
		</div>
		<nav class="botoes">
			<button onclick="window.location.href='<?= base_url('artesa/apoio') ?>'">
				<span class="material-symbols-outlined align-middle" style="font-size:1em;">arrow_back</span>
				Voltar
			</button>
		</nav>
		<div class="botLog">
			<button onclick="window.location.href='<?= base_url('logout') ?>'" id="btnHeaderSair">Sair</button>
		</div>
	</header>

<div class="dashboardHeader">
		<h2>
			<span class="material-symbols-outlined align-middle me-2" style="font-size: 1.2em;">bar_chart</span>
			Minhas Vendas
		</h2>
		<p>Acompanhe tudo o que você vendeu. Simples e sem complicação.</p>
	</div>

	<main class="container py-5">

		<div class="seletorPeriodo mb-5">
			<button class="btnPeriodo btnPeriodoAtivo" id="btn-mes-atual" aria-pressed="true">
				Este Mês
			</button>
			<button class="btnPeriodo" id="btn-mes-anterior">
				Mês Passado
			</button>
			<button class="btnPeriodo" id="btn-total">
				Total Geral
			</button>
		</div>

<div class="row g-4 mb-5">
			<div class="col-12 col-sm-6 col-lg-3">
				<div class="cardMetrica">
					<div class="metricaIcone" style="background-color: #fdf6f6;">
						<span class="material-symbols-outlined text-vinho">payments</span>
					</div>
					<p class="metricaRotulo">Total Faturado</p>
					<div class="metricaValor">R$ <?= number_format($metricas->total_faturado ?? 0, 2, ',', '.') ?></div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-lg-3">
				<div class="cardMetrica">
					<div class="metricaIcone" style="background-color: #eaf0ea;">
						<span class="material-symbols-outlined text-oliva">inventory_2</span>
					</div>
					<p class="metricaRotulo">Total de Pedidos</p>
					<div class="metricaValor"><?= (int)($metricas->total_pedidos ?? 0) ?></div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-lg-3">
				<div class="cardMetrica">
					<div class="metricaIcone" style="background-color: #fff3cd;">
						<span class="material-symbols-outlined" style="color: #856404;">hourglass_empty</span>
					</div>
					<p class="metricaRotulo">Pedidos Pendentes</p>
					<div class="metricaValor" style="color: #856404;"><?= (int)($metricas->pedidos_pendentes ?? 0) ?></div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-lg-3">
				<div class="cardMetrica">
					<div class="metricaIcone" style="background-color: #eaf0ea;">
						<span class="material-symbols-outlined text-oliva">check_circle</span>
					</div>
					<p class="metricaRotulo">Pedidos Entregues</p>
					<div class="metricaValor" style="color: #4a6741;"><?= (int)($metricas->pedidos_entregues ?? 0) ?></div>
				</div>
			</div>
		</div>

<div class="tabelaVendas">
			<div class="tabelaVendasCabecalho">
				<h3>Histórico de Pedidos</h3>
			</div>

			<div class="table-responsive">
				<table class="table tabelaSimples" aria-label="Tabela com os pedidos recebidos">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Cliente</th>
							<th scope="col">Data</th>
							<th scope="col">Pagamento</th>
							<th scope="col">Entrega</th>
							<th scope="col" class="text-end">Valor</th>
						</tr>
					</thead>
					<tbody>
					<?php if (empty($pedidos)): ?>
					<tr>
						<td colspan="6" class="text-center text-fendi py-4">Nenhum pedido encontrado.</td>
					</tr>
					<?php else: ?>
					<?php foreach ($pedidos as $pedido): ?>
					<tr>
						<td>#<?= $pedido->id ?></td>
						<td><?= esc($pedido->cliente_nome ?? 'N/A') ?></td>
						<td><?= date('d/m/Y', strtotime($pedido->created_at)) ?></td>
						<td><span class="badgeStatus <?= $pedido->status_pagamento === 'pago' ? 'badge-verde' : 'badge-amarelo' ?>"><?= ucfirst($pedido->status_pagamento) ?></span></td>
						<td><span class="badgeStatus badge-azul-tabela"><?= esc($pedido->status_entrega) ?></span></td>
						<td class="text-end fw-bold text-cafe">R$ <?= number_format($pedido->valor_total, 2, ',', '.') ?></td>
					</tr>
					<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
					<tfoot>
						<tr class="tabelaRodape">
							<td colspan="5"><strong>Total geral</strong></td>
							<td class="text-end"><strong class="totalMes">R$ <?= number_format($metricas->total_faturado ?? 0, 2, ',', '.') ?></strong></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>

		<div class="mensagemEncorajadora mt-4">
			<span class="material-symbols-outlined text-vinho" style="font-size: 2em;">auto_awesome</span>
			<div>
				<strong>Você está indo muito bem!</strong>
				<p>Continue caprichando nas suas peças. Cada venda é o resultado do seu talento.</p>
			</div>
		</div>

	</main>

<footer class="footerBottom p-4 mt-auto bg-offwhite border-top border-fendi">
		<p>Feito com <span class="material-symbols-outlined footerHeart text-vinho">favorite</span> por Crochettei ©
			2026</p>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmxc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
		crossorigin="anonymous"></script>
</body>

</html>
