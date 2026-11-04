<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Meus pedidos no Crochettei — acompanhe o status de cada compra.">
	<title>Crochettei — Meus Pedidos</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

	<link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/dashboardCliente.css') ?>">
</head>

<body class="paginaDashboardCliente">

<header class="navBar">
		<div class="logo">
			<h1>Crochettei</h1>
		</div>
		<nav class="botoes">
			<button onclick="window.location.href='<?= base_url('cliente/dashboard') ?>'" aria-label="Voltar ao painel">
				<span class="material-symbols-outlined align-middle" style="font-size:1em;">arrow_back</span>
				Meu Painel
			</button>
			<button onclick="window.location.href='<?= base_url('catalogo') ?>'">Catálogo</button>
		</nav>
		<div class="botLog">
			<button onclick="window.location.href='<?= base_url('logout') ?>'" id="btnHeaderSair">Sair</button>
		</div>
	</header>

<?php if (session()->getFlashdata('sucesso')): ?>
<div class="alert alert-success mx-3 mt-3"><?= esc(session()->getFlashdata('sucesso')) ?></div>
<?php endif; ?>

<section class="bannerCliente">
		<div class="container">
			<h2>
				<span class="material-symbols-outlined align-middle me-2">local_mall</span>
				Meus Pedidos
			</h2>
			<p>Acompanhe o status de todas as suas compras.</p>
		</div>
	</section>

	<main class="container py-5 flex-grow-1">

<?php if (empty($pedidos)): ?>
<div class="text-center py-5">
			<span class="material-symbols-outlined" style="font-size:4rem; color:#b5a89e;">local_mall</span>
			<p class="text-fendi mt-3 fs-5">Você ainda não fez nenhum pedido.</p>
			<a href="<?= base_url('catalogo') ?>" class="btnCheckout text-decoration-none mt-3 d-inline-block">Ver Catálogo</a>
		</div>
<?php else: ?>

		<div class="pedidosClienteBox">
			<h3>
				<span class="material-symbols-outlined text-vinho">local_mall</span>
				Todos os Pedidos
			</h3>

		<?php foreach ($pedidos as $pedido): ?>
			<div class="pedidoClienteItem">
				<div class="pedidoClienteInfo">
					<strong>Pedido #<?= $pedido->id ?></strong>
					<span>
						R$ <?= number_format($pedido->valor_total, 2, ',', '.') ?>
						| <?= date('d/m/Y', strtotime($pedido->created_at)) ?>
					</span>
					<?php if (!empty($pedido->artesa_nome)): ?>
					<span class="text-fendi small">Por: <?= esc($pedido->artesa_nome) ?></span>
					<?php endif; ?>
				</div>
				<div>
					<?php
					$statusClass = match($pedido->status_entrega ?? '') {
						'entregue'          => 'badge-verde',
						'enviado'           => 'badge-azul',
						'aguardando_coleta' => 'badge-azul',
						'cancelado'         => 'badge-vermelho',
						default             => 'badge-amarelo',
					};
					$statusLabel = match($pedido->status_entrega ?? '') {
						'em_producao'       => 'Em Produção',
						'aguardando_coleta' => 'Aguardando Coleta',
						'enviado'           => 'Enviado',
						'entregue'          => 'Entregue',
						'cancelado'         => 'Cancelado',
						default             => ucwords(str_replace('_', ' ', $pedido->status_entrega ?? 'Aguardando')),
					};
					?>
					<span class="badgeStatusCliente <?= $statusClass ?>"><?= $statusLabel ?></span>
					<?php if ($pedido->status_pagamento === 'pago'): ?>
					<span class="badgeStatusCliente badge-verde ms-1">Pago</span>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>

		</div>

<?php endif; ?>

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
