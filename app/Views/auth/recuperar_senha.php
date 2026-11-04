<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Recupere o acesso à sua conta Crochettei. Digite seu e-mail ou CPF e enviaremos as instruções.">
	<title>Crochettei — Recuperar Senha</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

<link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/recuperarSenha.css') ?>">
</head>

<body class="paginaFormulario">

<header class="navBar">
		<div class="logo">
			<h1>Crochettei</h1>
		</div>
		<nav class="botoes">
			<button onclick="window.location.href='<?= base_url('/') ?>'">Início</button>
		</nav>
		<div class="botLog">
			<button onclick="window.location.href='<?= base_url('login') ?>'" id="btnHeaderLogin">Login</button>
		</div>
	</header>

<div class="areaRecuperar">
		<div class="cardRecuperar">

<div id="estadoFormulario">

			<div class="iconeRecuperar">
				<span class="material-symbols-outlined">lock_reset</span>
			</div>

			<h2>Esqueceu a senha?</h2>
			<p>
				Sem problema! Digite o seu <strong>e-mail</strong> ou <strong>CPF</strong>
				cadastrado e enviaremos as instruções para criar uma nova senha.
			</p>

			<form action="<?= base_url('recuperar-senha') ?>" method="post" novalidate id="formRecuperar">
			<?= csrf_field() ?>
				<div class="grupoForm mb-3">
					<label for="recuperarIdentificador">Seu e-mail ou CPF</label>
					<input
						type="text"
						id="recuperarIdentificador"
						name="identificador"
						class="inputGrande"
						placeholder="seu@email.com ou 000.000.000-00"
						required
						autocomplete="username"
						inputmode="email">
				</div>
				<button type="submit" class="btnRecuperar" id="btnRecuperar">
					Recuperar minha senha
				</button>
			</form>

			<div class="avisoProcesso">
				<span class="material-symbols-outlined">info</span>
				<p>
					Você receberá um e-mail com um link seguro para redefinir sua senha.
					Verifique também a caixa de spam, caso não encontre na caixa de entrada.
				</p>
			</div>

			<a href="<?= base_url('login') ?>" class="linkVoltar" id="linkVoltarLogin">
				<span class="material-symbols-outlined">arrow_back</span>
				Voltar ao Login
			</a>

		</div>

		</div>
	</div>

	<div class="footerBottom p-4 border-top border-fendi bg-white">
		<p>Feito com <span class="material-symbols-outlined footerHeart text-vinho">favorite</span> por Crochettei © 2026</p>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmxc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
		crossorigin="anonymous"></script>
</body>

</html>
