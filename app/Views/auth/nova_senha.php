<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Redefina sua senha do Crochettei.">
	<title>Crochettei — Nova Senha</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
				
				<?php if (session()->getFlashdata('erro')): ?>
					<div class="alert alert-danger" role="alert">
						<?= session()->getFlashdata('erro') ?>
					</div>
				<?php endif; ?>

				<div class="iconeRecuperar">
					<span class="material-symbols-outlined">password</span>
				</div>

				<h2>Criar nova senha</h2>
				<p>
					Crie uma nova senha para acessar sua conta. Certifique-se de escolher uma senha forte e segura, com pelo menos 6 caracteres.
				</p>

				<form action="<?= base_url('redefinir-senha/' . esc($token)) ?>" method="post" id="formNovaSenha">
					<?= csrf_field() ?>
					<div class="grupoForm mb-3">
						<label for="senha">Nova senha</label>
						<input
							type="password"
							id="senha"
							name="senha"
							class="inputGrande"
							placeholder="Sua nova senha secreta"
							required
							minlength="6">
					</div>
					<div class="grupoForm mb-4">
						<label for="confirmarSenha">Repita a nova senha</label>
						<input
							type="password"
							id="confirmarSenha"
							name="confirmarSenha"
							class="inputGrande"
							placeholder="Digite exatamente igual"
							required
							minlength="6">
					</div>

					<button type="submit" class="btnRecuperar" id="btnNovaSenha">
						Salvar nova senha
					</button>
				</form>

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
