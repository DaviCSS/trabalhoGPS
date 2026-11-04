<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Seu painel no Crochettei — veja seus pedidos e adicione novos produtos com facilidade.">
	<title>Crochettei — Meu Ateliê</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

<link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
</head>

<body class="paginaDashboard">

<header class="navBar">
		<div class="logo">
			<h1>Crochettei</h1>
		</div>
		<div class="botLog">
			<button onclick="window.location.href='<?= base_url('logout') ?>'" id="btnHeaderSair" aria-label="Sair da conta">
				<span class="material-symbols-outlined align-middle" style="font-size:1em; margin-right:0.3em;">logout</span>
				Sair
			</button>
		</div>
	</header>

<?php if (session()->getFlashdata('sucesso')): ?>
<div class="alert alert-success m-3" role="alert"><?= esc(session()->getFlashdata('sucesso')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('erro')): ?>
<div class="alert alert-danger m-3" role="alert"><?= esc(session()->getFlashdata('erro')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('aviso')): ?>
<div class="alert alert-warning m-3" role="alert"><?= esc(session()->getFlashdata('aviso')) ?></div>
<?php endif; ?>

<div class="dashboardHeader">
		<h2>Olá, <?= esc($usuario->nome ?? 'Artesã') ?>!</h2>
		<p>Bem-vinda ao seu ateliê digital.</p>
	</div>

<div class="container mt-4 mb-0 px-3 px-md-4">
		<div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
			<div class="col">
				<div class="card border-0 shadow-sm rounded-4 text-center p-3">
					<div class="fs-4 fw-bold text-vinho"><?= (int)($totalProdutos ?? 0) ?></div>
					<div class="text-fendi small">Produtos Ativos</div>
				</div>
			</div>
			<div class="col">
				<div class="card border-0 shadow-sm rounded-4 text-center p-3">
					<div class="fs-4 fw-bold text-vinho"><?= (int)($totalPedidos ?? 0) ?></div>
					<div class="text-fendi small">Total de Pedidos</div>
				</div>
			</div>
			<div class="col">
				<div class="card border-0 shadow-sm rounded-4 text-center p-3">
					<div class="fs-4 fw-bold text-vinho">R$ <?= number_format($totalFaturado ?? 0, 2, ',', '.') ?></div>
					<div class="text-fendi small">Total Faturado</div>
				</div>
			</div>
			<div class="col">
				<div class="card border-0 shadow-sm rounded-4 text-center p-3">
					<div class="fs-4 fw-bold text-vinho"><?= (int)($pedidosPendentes ?? 0) ?></div>
					<div class="text-fendi small">Pedidos Pendentes</div>
				</div>
			</div>
		</div>
	</div>

	<?php
	$metaVendas = 10;
	$progresso = min(100, ($totalPedidos / $metaVendas) * 100);
	$atingiuMeta = $totalPedidos >= $metaVendas;
	?>
	<div class="container mt-2 mb-2 px-3 px-md-4">
		<div class="card border-0 shadow-sm rounded-4 p-3 mb-3">
			<h5 class="text-vinho fw-bold mb-2">
				<span class="material-symbols-outlined align-middle me-1">local_fire_department</span>
				Termômetro de Sucesso
			</h5>
			<p class="text-fendi small mb-2">Acompanhe seu crescimento! Nossa primeira meta para você é de <?= $metaVendas ?> vendas.</p>
			
			<div class="progress" style="height: 25px; border-radius: 20px; background-color: #f1ede1;">
				<div class="progress-bar" role="progressbar" style="width: <?= $progresso ?>%; background-color: #8C2C36; font-weight: bold;" aria-valuenow="<?= $progresso ?>" aria-valuemin="0" aria-valuemax="100">
					<?= $totalPedidos ?> / <?= $metaVendas ?> vendas
				</div>
			</div>
			
			<?php if ($atingiuMeta): ?>
			<div class="alert mt-3 mb-0 d-flex align-items-center gap-2" role="alert" style="border-radius: 15px; background-color: #fff3cd; border: 1px solid #ffe69c; color: #664d03;">
				<span class="material-symbols-outlined" style="font-size: 2.2em; color: #ffc107;">workspace_premium</span>
				<div style="font-size: 0.9em;">
					<strong>Parabéns! Suas vendas estão decolando!</strong><br>
					Com esse sucesso, que tal dar o próximo passo? Considere se formalizar tirando o <strong>MEI (Microempreendedor Individual)</strong> para garantir direitos como aposentadoria e auxílio-doença!
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>

<main class="flex-grow-1">
		<div class="areaAcoesPrincipais mt-4 px-3">

<a href="<?= base_url('artesa/pedidos') ?>"
				class="btnAcaoPrincipal btnAcaoPrincipal--primario"
				id="btnVerPedidos"
				aria-label="Ver meus pedidos">
				<div class="btnAcaoIcone">
					<span class="material-symbols-outlined">inventory_2</span>
				</div>
				<div class="btnAcaoTexto">
					<span class="btnAcaoTitulo">Ver Meus Pedidos</span>
					<span class="btnAcaoDesc">Veja o que os clientes pediram e o que está pronto para enviar.</span>
				</div>
				<span class="material-symbols-outlined btnAcaoSeta" style="color: rgba(253,251,247,0.6);">arrow_forward_ios</span>
			</a>

<a href="<?= base_url('artesa/produtos/criar') ?>"
				class="btnAcaoPrincipal"
				id="btnVenderProduto"
				aria-label="Cadastrar um novo produto para vender">
				<div class="btnAcaoIcone">
					<span class="material-symbols-outlined">add_circle</span>
				</div>
				<div class="btnAcaoTexto">
					<span class="btnAcaoTitulo">Vender um Novo Produto</span>
					<span class="btnAcaoDesc">Cadastre uma nova peça para aparecer no seu catálogo.</span>
				</div>
				<span class="material-symbols-outlined btnAcaoSeta">arrow_forward_ios</span>
			</a>

<a href="<?= base_url('artesa/produtos') ?>"
				class="btnAcaoPrincipal"
				id="btnMeusProdutos"
				aria-label="Ver meus produtos">
				<div class="btnAcaoIcone">
					<span class="material-symbols-outlined">storefront</span>
				</div>
				<div class="btnAcaoTexto">
					<span class="btnAcaoTitulo">Meus Produtos</span>
					<span class="btnAcaoDesc">Gerencie seu catálogo de peças.</span>
				</div>
				<span class="material-symbols-outlined btnAcaoSeta">arrow_forward_ios</span>
			</a>

		</div>

		<div class="linkIrCompras" role="navigation" aria-label="Acessar painel de cliente">
			<a href="<?= base_url('cliente/dashboard') ?>"
				class="btnIrCompras"
				id="btnIrParaVitrine"
				aria-label="Acessar painel de cliente">
				<span class="material-symbols-outlined btnIrComprasIcone">person</span>
				<span class="btnIrComprasTexto">Visão Cliente<small>Acessar painel de compras e catálogo</small></span>
			</a>
		</div>

	</main>

<footer class="rodapeAjudante">
		<a href="<?= base_url('artesa/apoio') ?>" id="linkAcessoAjudante" class="linkAjudanteCard" aria-label="Ir para configurações da conta e área do ajudante">
			<span class="material-symbols-outlined linkAjudanteCardIcone">settings</span>
			<span class="linkAjudanteCardTexto">
				Configurações da Minha Conta
				<small>Ajudante: altere dados, fotos e configurações</small>
			</span>
			<span class="material-symbols-outlined" style="color:#b5a89e; font-size:1.1em;">chevron_right</span>
		</a>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmxc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
		crossorigin="anonymous"></script>
</body>

</html>
