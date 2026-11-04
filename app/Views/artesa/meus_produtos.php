<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Meus Produtos no Crochettei">
	<title>Crochettei — Meus Produtos</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

<link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/meusProdutos.css') ?>">
</head>

<body class="paginaMeusProdutos bg-offwhite">

<header class="navBar">
		<div class="logo">
			<h1>Crochettei</h1>
		</div>
		<nav class="botoes">
			<button onclick="window.location.href='<?= base_url('artesa/dashboard') ?>'" aria-label="Voltar ao meu painel">
				<span class="material-symbols-outlined align-middle" style="font-size:1em;">arrow_back</span>
				Meu Painel
			</button>
		</nav>
		<div class="botLog">
			<button onclick="window.location.href='<?= base_url('logout') ?>'" id="btnHeaderSair">Sair</button>
		</div>
	</header>

<?php if (session()->getFlashdata('sucesso')): ?>
<div class="alert alert-success mx-3 mt-3" role="alert"><?= esc(session()->getFlashdata('sucesso')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('erro')): ?>
<div class="alert alert-danger mx-3 mt-3" role="alert"><?= esc(session()->getFlashdata('erro')) ?></div>
<?php endif; ?>

	<main class="container py-5 flex-grow-1">

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-4 w-100">
			<div>
				<h2 class="fs-jost text-cafe fw-bold mb-2">Meus Produtos cadastrados</h2>
				<p class="text-fendi fs-5 mb-0">Gerencie seu estoque, edite detalhes e adicione novas peças ao seu catálogo.</p>
			</div>
			<a href="<?= base_url('artesa/produtos/criar') ?>" class="btnNovoProduto btn-gigante text-decoration-none">
				<span class="material-symbols-outlined fs-3 align-middle">add_circle</span>
				Novo Produto
			</a>
		</div>

<?php if (empty($produtos)): ?>
<div class="text-center py-5">
			<span class="material-symbols-outlined" style="font-size:4rem; color:#b5a89e;">inventory_2</span>
			<p class="text-fendi mt-3 fs-5">Você ainda não tem produtos cadastrados.</p>
			<a href="<?= base_url('artesa/produtos/criar') ?>" class="btnNovoProduto text-decoration-none">Cadastrar Primeiro Produto</a>
		</div>
<?php else: ?>
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 w-100">
		<?php foreach ($produtos as $produto): ?>
			<div class="col">
				<div class="cardProduto h-100 shadow-sm border-0 rounded-4 overflow-hidden position-relative">
					<div class="badge-status <?= ($produto->estoque > 0 || in_array($produto->tipo, ['sob_encomenda', 'misto'])) ? 'bg-verde text-white' : 'bg-esgotado' ?>">
						<?= ($produto->estoque > 0) ? 'Disponível' : (in_array($produto->tipo, ['sob_encomenda', 'misto']) ? 'Sob Encomenda' : 'Esgotado') ?>
					</div>
					<?php if ($produto->imagem_path): ?>
					<img src="<?= base_url($produto->imagem_path) ?>" class="card-img-top imgProduto <?= ($produto->estoque <= 0 && $produto->tipo === 'pronta_entrega') ? 'imgEsgotado' : '' ?>" alt="<?= esc($produto->nome) ?>">
					<?php else: ?>
					<div class="imgProduto d-flex align-items-center justify-content-center bg-light">
						<span class="material-symbols-outlined" style="font-size:3rem; color:#b5a89e;">image</span>
					</div>
					<?php endif; ?>
					<div class="card-body d-flex flex-column p-4">
						<h4 class="card-title fs-jost text-cafe fw-bold mb-2"><?= esc($produto->nome) ?></h4>
						<p class="card-price text-vinho fs-4 fw-bold mb-4">R$ <?= number_format($produto->preco, 2, ',', '.') ?></p>
						<p class="text-fendi small mb-2">Estoque: <?= (int)$produto->estoque ?></p>

						<div class="mt-auto d-flex gap-2">
							<a href="<?= base_url('artesa/produtos/editar/' . $produto->id) ?>" class="btnAcao btnEditar flex-grow-1 d-flex align-items-center justify-content-center gap-2 text-decoration-none" aria-label="Editar Produto">
								<span class="material-symbols-outlined">edit</span> Editar
							</a>
							<form action="<?= base_url('artesa/produtos/excluir/' . $produto->id) ?>" method="post" onsubmit="return confirm('Tem certeza que deseja remover este produto?')" class="m-0 p-0 d-flex">
							<?= csrf_field() ?>
								<button type="submit" class="btnAcao btnExcluir d-flex align-items-center justify-content-center" aria-label="Excluir Produto" title="Excluir">
									<span class="material-symbols-outlined">delete</span>
								</button>
							</form>
						</div>
					</div>
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
