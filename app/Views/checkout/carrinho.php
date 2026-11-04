<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Seu carrinho de compras no Crochettei — revise os itens e finalize seu pedido.">
	<title>Crochettei — Carrinho de Compras</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

<link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/carrinho.css') ?>">
</head>

<body class="paginaCarrinho">

<header class="navBar">
		<div class="logo">
			<h1>Crochettei</h1>
		</div>
		<nav class="botoes">

			<button onclick="window.location.href='<?= base_url('catalogo') ?>'">Catálogo</button>
			<button onclick="window.location.href='<?= base_url('carrinho') ?>'" style="position: relative;" aria-label="Carrinho">
				<span class="material-symbols-outlined align-middle fs-4">shopping_cart</span>
				<?php if (!empty($qtdItens) && $qtdItens > 0): ?>
				<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="font-size: 0.65em; background-color: #932121; margin-top: 5px; margin-left: -5px; color: #fff;"><?= (int)$qtdItens ?></span>
				<?php endif; ?>
			</button>
		</nav>
		<div class="botLog">
			<button onclick="window.location.href='<?= base_url('logout') ?>'" id="btnHeaderSair">Sair</button>
		</div>
	</header>

<?php if (session()->getFlashdata('sucesso')): ?>
<div class="alert alert-success mx-3 mt-3"><?= esc(session()->getFlashdata('sucesso')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('aviso')): ?>
<div class="alert alert-warning mx-3 mt-3"><?= esc(session()->getFlashdata('aviso')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('erro')): ?>
<div class="alert alert-danger mx-3 mt-3"><?= esc(session()->getFlashdata('erro')) ?></div>
<?php endif; ?>

<div class="dashboardHeader">
		<h2>
			<span class="material-symbols-outlined align-middle me-2" style="font-size: 1.2em;">shopping_cart</span>
			Meu Carrinho
		</h2>
		<p>Revise os itens antes de finalizar.</p>
	</div>

	<main class="container py-5">

<?php if (empty($itens)): ?>
<div class="text-center py-5">
			<span class="material-symbols-outlined" style="font-size:5rem; color:#b5a89e;">shopping_cart</span>
			<p class="text-fendi mt-3 fs-4">Seu carrinho está vazio.</p>
			<a href="<?= base_url('catalogo') ?>" class="btnCheckout text-decoration-none mt-3 d-inline-block" style="background-color: #4a6741; border-color: #4a6741;">Ver Catálogo</a>
		</div>
<?php else: ?>

		<div class="row g-4">

<div class="col-12 col-lg-8">

		<?php foreach ($itens as $item): ?>
		<div class="carrinhoItem">
				<div class="carrinhoItemFoto">
					<?php if ($item['imagem_path']): ?>
					<?php $imgSrc = str_starts_with($item['imagem_path'], 'http') ? $item['imagem_path'] : base_url($item['imagem_path']); ?>
					<img src="<?= $imgSrc ?>" alt="<?= esc($item['nome']) ?>" style="width:80px; height:80px; object-fit:cover; border-radius:8px;">
					<?php else: ?>
					<div class="fotoPlaceholder">
						<span class="material-symbols-outlined text-vinho" style="font-size: 2.5em;">styler</span>
					</div>
					<?php endif; ?>
				</div>
				<div class="carrinhoItemInfo">
					<h3><?= esc($item['nome']) ?></h3>
					<p class="preco-unitario">R$ <?= number_format($item['preco'], 2, ',', '.') ?> cada</p>
				</div>

				<form action="<?= base_url('carrinho/atualizar') ?>" method="post" class="carrinhoItemQtd">
				<?= csrf_field() ?>
					<input type="hidden" name="produto_id" value="<?= (int)$item['produto_id'] ?>">
					<div class="controleQtd">
						<input type="number" name="quantidade" class="inputQtd" value="<?= (int)$item['quantidade'] ?>" min="1" max="99" onchange="this.form.submit()">
					</div>
				</form>

				<div class="carrinhoItemPreco">
					<span class="precoTotal">R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></span>
					<form action="<?= base_url('carrinho/remover/' . $item['produto_id']) ?>" method="post">
					<?= csrf_field() ?>
						<button type="submit" class="btnRemover" aria-label="Remover <?= esc($item['nome']) ?> do carrinho">
							<span class="material-symbols-outlined">delete</span>
						</button>
					</form>
				</div>
			</div>
		<?php endforeach; ?>

<div class="mt-4">
				<a href="<?= base_url('catalogo') ?>" class="linkContinuar">
					<span class="material-symbols-outlined align-middle me-1">arrow_back</span>
					Continuar Comprando
				</a>
			</div>

		</div>

<div class="col-12 col-lg-4">
				<div class="resumoPedido">
					<h3 class="resumoTitulo">Resumo do Pedido</h3>

					<div class="resumoLinha">
						<span><?= count($itens) ?> iten(s)</span>
						<span>R$ <?= number_format($total, 2, ',', '.') ?></span>
					</div>
					<div class="resumoLinha">
						<span>Frete estimado</span>
						<span class="text-oliva fw-bold">Calculado no Checkout</span>
					</div>

					<hr class="resumoDivisor">

					<div class="resumoLinha resumoTotal">
						<span>Total</span>
						<span>R$ <?= number_format($total, 2, ',', '.') ?></span>
					</div>

<a href="<?= base_url('checkout') ?>" class="btnCheckout" id="btnIrCheckout">
						<span class="material-symbols-outlined align-middle me-2">lock</span>
						Ir para o Checkout
					</a>

<div class="selosConfianca">
						<div class="seloItem">
							<span class="material-symbols-outlined text-oliva">verified_user</span>
							<span>Compra Segura</span>
						</div>
						<div class="seloItem">
							<span class="material-symbols-outlined text-oliva">payments</span>
							<span>Pix ou Cartão</span>
						</div>
					</div>
				</div>
			</div>

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
