<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Área do Cliente - Crochettei">
	<title>Crochettei — Minha Conta</title>

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
			<button onclick="window.location.href='<?= base_url('catalogo') ?>'">Catálogo</button>
			<button onclick="window.location.href='<?= base_url('carrinho') ?>'" style="position: relative;" aria-label="Acessar Meu Carrinho">
				<span class="material-symbols-outlined align-middle fs-4">shopping_cart</span>
			</button>
		</nav>
		<div class="botLog">
			<button onclick="window.location.href='<?= base_url('logout') ?>'" id="btnHeaderSair">Sair</button>
		</div>
	</header>

<?php if (session()->getFlashdata('sucesso')): ?>
<div class="alert alert-success m-3"><?= esc(session()->getFlashdata('sucesso')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('aviso')): ?>
<div class="alert alert-warning m-3"><?= esc(session()->getFlashdata('aviso')) ?></div>
<?php endif; ?>

<section class="bannerCliente">
		<div class="container">
			<h2>Olá, <?= esc($usuario->nome ?? 'Cliente') ?>!</h2>
			<p>Que bom ter você de volta ao Crochettei.</p>
		</div>
	</section>

	<main class="container py-5 flex-grow-1">

<div class="row mb-5">
			<div class="col-12">
				<div class="atalhoCards">

<a href="<?= base_url('catalogo') ?>" class="cardAtalho atalhoDestaque">
						<div class="iconeAtalho">
							<span class="material-symbols-outlined">storefront</span>
						</div>
						<div class="textoAtalho">
							<h3>Ver Catálogo de Produtos</h3>
							<p>Descubra novas peças artesanais.</p>
						</div>
					</a>

<a href="<?= base_url('cliente/pedidos') ?>" class="cardAtalho atalhoDados">
						<div class="iconeAtalho">
							<span class="material-symbols-outlined">local_mall</span>
						</div>
						<div class="textoAtalho">
							<h3>Meus Pedidos</h3>
							<p>Acompanhe o status das suas compras.</p>
						</div>
					</a>

				</div>
			</div>
		</div>

<div class="row">
			<div class="col-12">
				<div class="pedidosClienteBox">
					<h3>
						<span class="material-symbols-outlined text-vinho">local_mall</span>
						Meus Pedidos Recentes
					</h3>

				<?php if (empty($pedidos)): ?>
					<p class="text-fendi text-center py-3">Você ainda não fez nenhum pedido.</p>
				<?php else: ?>
				<?php foreach ($pedidos as $pedido): ?>
					<div class="pedidoClienteItem">
						<div class="pedidoClienteInfo">
							<strong>Pedido #<?= $pedido->id ?></strong>
							<span>R$ <?= number_format($pedido->valor_total, 2, ',', '.') ?> | <?= date('d/m/Y', strtotime($pedido->created_at)) ?></span>
						</div>
						<div>
							<span class="badgeStatusCliente <?= $pedido->status_entrega === 'entregue' ? 'badge-verde' : 'badge-amarelo' ?>">
								<?= ucwords(str_replace('_', ' ', $pedido->status_entrega)) ?>
							</span>
						</div>
					</div>
				<?php endforeach; ?>
				<?php endif; ?>

				</div>
			</div>
		</div>

	</main>

	<?php if (session()->get('user_is_artesa')): ?>
		<div class="linkIrCompras" role="navigation" aria-label="Acessar meu ateliê">
			<a href="<?= base_url('artesa/dashboard') ?>"
				class="btnIrCompras btnIrCompras--volta"
				id="btnVoltarAtelie"
				aria-label="Acessar meu ateliê">
				<span class="material-symbols-outlined btnIrComprasIcone">storefront</span>
				<span class="btnIrComprasTexto">Visão Artesã<small>Voltar para o Meu Ateliê</small></span>
			</a>
		</div>
	<?php endif; ?>

<footer class="footerBottom p-4 mt-auto bg-offwhite border-top border-fendi">
		<p>Feito com <span class="material-symbols-outlined footerHeart text-vinho">favorite</span> por Crochettei ©
			2026</p>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmxc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
		crossorigin="anonymous"></script>
</body>

</html>
