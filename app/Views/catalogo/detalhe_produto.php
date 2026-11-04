<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Detalhes de <?= esc($produto->nome) ?> no Crochettei">
	<title>Crochettei — <?= esc($produto->nome) ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

	<link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/catalogo.css') ?>">
</head>

<body class="paginaCatalogo">

<header class="navBar">
		<div class="logo">
			<h1><a href="<?= base_url('/') ?>" class="text-decoration-none text-dark">Crochettei</a></h1>
		</div>
		<nav class="botoes">
			<?php if (session()->get('logado')): ?>
			<button onclick="window.location.href='<?= base_url('cliente/dashboard') ?>'">Meu Painel</button>
			<?php endif; ?>
			<button onclick="window.location.href='<?= base_url('catalogo') ?>'">Catálogo</button>
			<button onclick="window.location.href='<?= base_url('carrinho') ?>'" style="position: relative;" aria-label="Carrinho">
				<span class="material-symbols-outlined align-middle fs-4">shopping_cart</span>
				<?php if (!empty($qtdCarrinho) && $qtdCarrinho > 0): ?>
				<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
					style="font-size: 0.65em; background-color: #932121; margin-top: 5px; margin-left: -5px; color: #fff;">
					<?= (int)$qtdCarrinho ?>
				</span>
				<?php endif; ?>
			</button>
		</nav>
		<div class="botLog">
			<?php if (session()->get('logado')): ?>
			<button onclick="window.location.href='<?= base_url('logout') ?>'" id="btnHeaderSair">Sair</button>
			<?php else: ?>
			<button onclick="window.location.href='<?= base_url('login') ?>'" id="btnHeaderLogin">Login</button>
			<?php endif; ?>
		</div>
	</header>

	<main class="container py-5 flex-grow-1">
		
		<nav aria-label="breadcrumb" class="mb-4">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= base_url('catalogo') ?>" class="text-vinho text-decoration-none">Catálogo</a></li>
				<li class="breadcrumb-item"><a href="<?= base_url('catalogo?categoria=' . $produto->categoria_id) ?>" class="text-vinho text-decoration-none"><?= esc($produto->categoria_nome ?? 'Categoria') ?></a></li>
				<li class="breadcrumb-item active text-fendi" aria-current="page"><?= esc($produto->nome) ?></li>
			</ol>
		</nav>

		<div class="row g-5">
			
			<div class="col-12 col-md-6">
				<div class="rounded-4 overflow-hidden shadow-sm" style="background-color: #f8fcf8;">
					<?php if ($produto->imagem_path): ?>
					<img src="<?= base_url($produto->imagem_path) ?>" alt="<?= esc($produto->nome) ?>" class="img-fluid w-100" style="object-fit: cover; max-height: 500px;">
					<?php else: ?>
					<div class="d-flex align-items-center justify-content-center" style="height: 400px;">
						<span class="material-symbols-outlined text-oliva" style="font-size: 5rem;">photo_camera</span>
					</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="col-12 col-md-6 d-flex flex-column">
				<h1 class="fs-jost fw-bold text-cafe mb-2"><?= esc($produto->nome) ?></h1>
				<p class="fs-5 text-vinho fw-bold mb-4">R$ <?= number_format($produto->preco, 2, ',', '.') ?></p>

				<div class="mb-4">
					<p class="mb-1 text-fendi">
						<span class="material-symbols-outlined align-middle fs-5 me-1">palette</span>
						Artesã: <strong><?= esc($produto->artesa_nome) ?></strong>
					</p>
					<p class="mb-1 text-fendi">
						<span class="material-symbols-outlined align-middle fs-5 me-1">schedule</span>
						Prazo de Confecção: <strong><?= (int)$produto->prazo_confeccao ?> dias</strong>
					</p>
					<p class="mb-1 text-fendi">
						<span class="material-symbols-outlined align-middle fs-5 me-1">inventory_2</span>
						Disponibilidade: <strong><?= $produto->tipo === 'pronta_entrega' ? 'Pronta Entrega' : ($produto->tipo === 'misto' ? 'Pronta Entrega, mas aceito encomendas' : 'Sob Encomenda') ?></strong>
					</p>
				</div>

				<div class="mb-4 p-4 rounded-3" style="background-color: #fdf6f6; border: 1px solid #eecaca;">
					<h4 class="fs-6 fw-bold text-cafe mb-2">Detalhes da Peça</h4>
					<p class="text-dark m-0" style="white-space: pre-line;"><?= esc($produto->descricao ?: 'Nenhuma descrição detalhada fornecida pela artesã.') ?></p>
				</div>

				<div class="mt-auto">
					<?php if ($produto->estoque > 0 || in_array($produto->tipo, ['sob_encomenda', 'misto'])): ?>
					<form action="<?= base_url('carrinho/adicionar') ?>" method="post" class="d-flex gap-3 align-items-center">
					<?= csrf_field() ?>
						<input type="hidden" name="produto_id" value="<?= (int)$produto->id ?>">
						<div class="d-flex align-items-center border border-2 border-fendi rounded-3 p-1">
							<span class="px-2 text-fendi">Qtd</span>
							<input type="number" name="quantidade" value="1" min="1" max="<?= $produto->tipo === 'pronta_entrega' ? (int)$produto->estoque : 99 ?>" class="form-control border-0 text-center fw-bold text-cafe" style="width: 70px; background: transparent; box-shadow: none;">
						</div>
						<button type="submit" class="btn btn-vinho px-4 py-3 fw-bold fs-5 rounded-pill flex-grow-1 d-flex justify-content-center align-items-center">
							<span class="material-symbols-outlined me-2">add_shopping_cart</span>
							Adicionar ao Carrinho
						</button>
					</form>
					<?php if ($produto->estoque <= 0 && in_array($produto->tipo, ['sob_encomenda', 'misto'])): ?>
					<p class="text-warning mt-2 text-center fs-6"><span class="material-symbols-outlined align-middle fs-6">schedule</span> Feito sob encomenda - Prazo: <?= (int)$produto->prazo_confeccao ?> dias</p>
					<?php elseif ($produto->tipo === 'pronta_entrega' || ($produto->tipo === 'misto' && $produto->estoque > 0)): ?>
					<p class="text-success mt-2 text-center fs-6"><span class="material-symbols-outlined align-middle fs-6">check_circle</span> Apenas <?= (int)$produto->estoque ?> em estoque!</p>
					<?php endif; ?>
					<?php else: ?>
					<button class="btn btn-secondary px-4 py-3 fw-bold fs-5 rounded-pill w-100 d-flex justify-content-center align-items-center" disabled>
						<span class="material-symbols-outlined me-2">remove_shopping_cart</span>
						Produto Esgotado
					</button>
					<?php endif; ?>
				</div>

			</div>
		</div>
	</main>

	<footer class="footerBottom p-4 mt-auto bg-offwhite border-top border-fendi">
		<p>Feito com <span class="material-symbols-outlined footerHeart text-vinho">favorite</span> por Crochettei © 2026</p>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmxc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
		crossorigin="anonymous"></script>
</body>

</html>
