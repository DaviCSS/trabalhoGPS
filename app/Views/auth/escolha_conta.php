<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Crie sua conta no Crochettei. Escolha se você é uma artesã que quer vender ou um cliente que quer comprar.">
	<title>Crochettei — Criar Conta</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

<link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/escolhaConta.css') ?>">
</head>

<body class="paginaEscolhaConta">

<header class="navBar">
		<div class="logo">
			<h1>Crochettei</h1>
		</div>
		<nav class="botoes">
			<button onclick="window.location.href='<?= base_url('/') ?>'">Início</button>
			<button onclick="window.location.href='<?= base_url('catalogo') ?>'">Catálogo</button>
		</nav>
		<div class="botLog">
			<button onclick="window.location.href='<?= base_url('login') ?>'" id="btnHeaderLogin">Já tenho conta</button>
		</div>
	</header>

	<main class="areaEscolha">

<div class="escolhaCabecalho">
			<div class="escolhaIcone">
				<span class="material-symbols-outlined">waving_hand</span>
			</div>
			<h2>Bem-vinda ao Crochettei!</h2>
			<p>
				Para criar sua conta, primeiro nos diga:<br>
				<strong>O que você quer fazer aqui?</strong>
			</p>
		</div>

<div class="escolhaCards">

<a href="<?= base_url('cadastro/artesa') ?>" class="cardEscolha cardEscolhaArtesa" id="escolhaArtesa">
				<div class="cardEscolhaIcone">
					<span class="material-symbols-outlined">palette</span>
				</div>

				<div class="cardEscolhaTexto">
					<h3>Sou Artesã</h3>
					<p>Quero criar minha conta para <strong>vender</strong> minhas peças de crochê.</p>
				</div>

				<ul class="cardEscolhaLista">
					<li>
						<span class="check">✓</span>
						Cadastro totalmente gratuito
					</li>
					<li>
						<span class="check">✓</span>
						Logística e pagamento resolvidos
					</li>
					<li>
						<span class="check">✓</span>
						Suporte fácil e acolhedor
					</li>
				</ul>

				<div class="cardEscolhaBotao">
					Quero vender meu crochê →
				</div>
			</a>

<div class="escolhaDivisor">
				<span>ou</span>
			</div>

<a href="<?= base_url('cadastro/cliente') ?>" class="cardEscolha cardEscolhaCliente" id="escolhaCliente">
				<div class="cardEscolhaIcone">
					<span class="material-symbols-outlined">shopping_bag</span>
				</div>

				<div class="cardEscolhaTexto">
					<h3>Sou Cliente</h3>
					<p>Quero criar minha conta para <strong>comprar</strong> peças artesanais feitas com carinho.</p>
				</div>

				<ul class="cardEscolhaLista">
					<li>
						<span class="check">✓</span>
						Peças únicas e artesanais
					</li>
					<li>
						<span class="check">✓</span>
						Compra segura e garantida
					</li>
					<li>
						<span class="check">✓</span>
						Entrega em todo o Brasil
					</li>
				</ul>

				<div class="cardEscolhaBotao">
					Quero comprar artesanato →
				</div>
			</a>

		</div>

<p class="escolhaRodapeLink">
			Já tem uma conta?
			<a href="<?= base_url('login') ?>" id="linkJaTenhoConta" class="text-vinho fw-bold text-decoration-none">
				Entrar agora →
			</a>
		</p>

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
