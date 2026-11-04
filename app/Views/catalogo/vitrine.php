<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Catálogo de peças artesanais feitas com carinho no Crochettei">
	<title>Crochettei — Catálogo de Produtos</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
			<h1>Crochettei</h1>
		</div>
		<nav class="botoes">
			<?php if (session()->get('logado')): ?>
				<?php if (session()->get('user_tipo') === 'artesa'): ?>
					<button onclick="window.location.href='<?= base_url('artesa/dashboard') ?>'">Meu Painel</button>
				<?php else: ?>
					<button onclick="window.location.href='<?= base_url('cliente/dashboard') ?>'">Meu Painel</button>
				<?php endif; ?>
			<?php else: ?>
				<button onclick="window.location.href='<?= base_url('/') ?>'">Home</button>
			<?php endif; ?>

			<button onclick="window.location.href='<?= base_url('carrinho') ?>'" style="position: relative;" aria-label="Acessar Meu Carrinho">
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

<?php if (session()->getFlashdata('sucesso')): ?>
<div class="alert alert-success mx-3 mt-3" role="alert"><?= esc(session()->getFlashdata('sucesso')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('aviso')): ?>
<div class="alert alert-warning mx-3 mt-3" role="alert"><?= esc(session()->getFlashdata('aviso')) ?></div>
<?php endif; ?>

<!-- Filtros por categoria -->
<div class="areaFiltros">
		<div class="container">
			<div class="filtroBtns justify-content-start justify-content-md-center px-3 px-md-0">
				<a href="<?= base_url('catalogo') ?>" class="btnFiltro <?= empty($categoriaSelecionada) ? 'ativo' : '' ?>">Todos</a>
				<?php foreach ($categorias as $categoria): ?>
				<a href="<?= base_url('catalogo?categoria_id=' . $categoria->id) ?>"
					class="btnFiltro <?= $categoriaSelecionada == $categoria->id ? 'ativo' : '' ?>">
					<?= esc($categoria->nome) ?>
				</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<main class="container mb-5 flex-grow-1">

		<div class="text-center my-5">
			<h2 class="tituloCatalogo mb-2">Descubra peças feitas à mão</h2>
			<p class="text-fendi fs-5">Artesanato especial para você e sua casa.</p>
		</div>

<?php if (empty($produtos)): ?>
<div class="text-center py-5">
			<span class="material-symbols-outlined" style="font-size:4rem; color:#b5a89e;">storefront</span>
			<p class="text-fendi mt-3 fs-5">Nenhum produto encontrado.</p>
		</div>
<?php else: ?>

		<div class="row g-4">
		<?php foreach ($produtos as $produto): ?>
			<div class="col-12 col-md-6 col-lg-4">
				<div class="cardProduto <?= ($produto->estoque <= 0 && $produto->tipo === 'pronta_entrega') ? 'card-esgotado' : '' ?>">
					<div class="imagemProduto <?= $produto->imagem_path ? '' : 'bg-placeholder-1' ?>">
						<?php if ($produto->estoque <= 0 && $produto->tipo === 'pronta_entrega'): ?>
						<span class="badge-esgotado">Esgotado</span>
						<?php elseif ($produto->estoque <= 0 && in_array($produto->tipo, ['sob_encomenda', 'misto'])): ?>
						<span class="badge-esgotado" style="background-color: #d97706;">Sob Encomenda</span>
						<?php endif; ?>
						<?php if ($produto->imagem_path): ?>
						<?php $imgSrc = str_starts_with($produto->imagem_path, 'http') ? $produto->imagem_path : base_url($produto->imagem_path); ?>
						<img src="<?= $imgSrc ?>"
							alt="<?= esc($produto->nome) ?>"
							style="width:100%; height:200px; object-fit:cover; background-color:#f8f6f0;"
							onerror="this.onerror=null; this.src='https://placehold.co/600x400/e2dcd5/2b1f1a?text=Foto+Indisponível';">
						<?php else: ?>
						<div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px;">
							<span class="material-symbols-outlined text-vinho fs-1">styler</span>
						</div>
						<?php endif; ?>
					</div>
					<div class="infoProduto">
						<h3><?= esc($produto->nome) ?></h3>
						<span class="artesaNome">Por <?= esc($produto->artesa_nome ?? 'Artesã') ?></span>
						<div class="precoProduto">R$ <?= number_format($produto->preco, 2, ',', '.') ?></div>

						<?php if ($produto->estoque > 0 || in_array($produto->tipo, ['sob_encomenda', 'misto'])): ?>
						<form action="<?= base_url('carrinho/adicionar') ?>" method="post">
						<?= csrf_field() ?>
							<input type="hidden" name="produto_id" value="<?= (int)$produto->id ?>">
							<input type="hidden" name="quantidade" value="1">
							<button type="submit" class="btnCarrinho">
								<span class="material-symbols-outlined">add_shopping_cart</span>
								Adicionar ao Carrinho
							</button>
						</form>
						<?php else: ?>
						<button class="btnCarrinho" disabled aria-disabled="true">
							<span class="material-symbols-outlined">remove_shopping_cart</span>
							Produto Esgotado
						</button>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		</div>

<?php endif; ?>

	</main>

	<footer class="footerBottom p-4 mt-auto bg-offwhite border-top border-fendi">
		<p>Feito com <span class="material-symbols-outlined footerHeart text-vinho">favorite</span> por Crochettei © 2026</p>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
