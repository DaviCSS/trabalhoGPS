<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Crie sua conta de artesã no Crochettei e comece a vender seu crochê online hoje mesmo.">
	<title>Crochettei — Cadastro de Artesã</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

<link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/forms.css') ?>">
</head>

<body class="paginaFormulario paginaArtesa">

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

<div class="areaFormulario">
		<div class="cardFormulario">

			<div class="cabecalhoCard">
				<div class="iconeFormulario artesa">
					<span class="material-symbols-outlined">palette</span>
				</div>
				<h2>Cadastro de Artesã</h2>
				<p>Crie sua conta gratuita e comece a vender seu crochê.</p>
			</div>

<?php if (session()->getFlashdata('erro')): ?>
<div class="alert alert-danger" role="alert">
    <?= esc(session()->getFlashdata('erro')) ?>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('sucesso')): ?>
<div class="alert alert-success" role="alert">
    <?= esc(session()->getFlashdata('sucesso')) ?>
</div>
<?php endif; ?>

<div class="notaRN artesa">
				<span class="material-symbols-outlined">info</span>
				<p>
					<strong>Por que pedimos esses dados?</strong>
					Precisamos do seu nome, CPF e endereço para garantir a segurança das vendas,
					organizar a logística de entrega e evitar perfis falsos. Seus dados são protegidos.
				</p>
			</div>

			<form action="<?= base_url('cadastro/artesa') ?>" method="post" class="mt-4" id="formCadastroArtesa">
			<?= csrf_field() ?>

<fieldset class="p-4 mb-4 bg-light border rounded border-fendi">
				<legend class="fs-5 fw-bold d-flex align-items-center gap-2 px-2 mb-3 text-cafe float-none w-auto fs-jost">
					<span class="material-symbols-outlined fs-5">person</span>
					Dados Pessoais
				</legend>

				<div class="grupoForm mb-3">
					<label for="nomeArtesa">Nome completo <span class="badgeObrigatorio">Obrigatório</span></label>
					<input type="text" id="nomeArtesa" name="nome" class="inputGrande" placeholder="Ex.: Maria da Silva" required autocomplete="name" value="<?= old('nome') ?>">
				</div>

				<div class="grupoForm mb-3">
					<label for="cpfArtesa">CPF <span class="badgeObrigatorio">Obrigatório</span></label>
					<input type="text" id="cpfArtesa" name="cpf" class="inputGrande" placeholder="Digite apenas os 11 números" pattern="[0-9]{11}" maxlength="11" required inputmode="numeric" value="<?= old('cpf') ?>">
					<div class="form-text text-fendi small">Digite apenas números, sem pontos ou traços.</div>
				</div>

				<div class="grupoForm mb-0">
					<label for="telefoneArtesa">Telefone / WhatsApp</label>
					<input type="tel" id="telefoneArtesa" name="telefone" class="inputGrande" placeholder="(27) 9 0000-0000" inputmode="tel" value="<?= old('telefone') ?>">
				</div>
			</fieldset>

<fieldset class="p-4 mb-4 bg-light border rounded border-fendi">
				<legend class="fs-5 fw-bold d-flex align-items-center gap-2 px-2 mb-3 text-cafe float-none w-auto fs-jost">
					<span class="material-symbols-outlined fs-5">location_on</span>
					Endereço
				</legend>

				<div class="grupoForm mb-3">
					<label for="cepArtesa">CEP <span class="badgeObrigatorio">Obrigatório</span></label>
					<input type="text" id="cepArtesa" name="cep" class="inputGrande" placeholder="00000-000" maxlength="9" required inputmode="numeric" value="<?= old('cep') ?>">
				</div>

				<div class="row g-2 mb-3">
					<div class="col-8">
						<div class="grupoForm">
							<label for="ruaArtesa">Rua / Avenida <span class="badgeObrigatorio">Obrigatório</span></label>
							<input type="text" id="ruaArtesa" name="rua" class="inputGrande" placeholder="Nome da sua rua" required value="<?= old('rua') ?>">
						</div>
					</div>
					<div class="col-4">
						<div class="grupoForm">
							<label for="numeroArtesa">Número <span class="badgeObrigatorio">Obrigatório</span></label>
							<input type="text" id="numeroArtesa" name="numero" class="inputGrande" placeholder="Ex.: 42" required inputmode="numeric" value="<?= old('numero') ?>">
						</div>
					</div>
				</div>

				<div class="grupoForm mb-3">
					<label for="bairroArtesa">Bairro <span class="badgeObrigatorio">Obrigatório</span></label>
					<input type="text" id="bairroArtesa" name="bairro" class="inputGrande" placeholder="Nome do seu bairro" required value="<?= old('bairro') ?>">
				</div>

				<div class="row g-2 mb-0">
					<div class="col-8">
						<div class="grupoForm">
							<label for="cidadeArtesa">Cidade <span class="badgeObrigatorio">Obrigatório</span></label>
							<input type="text" id="cidadeArtesa" name="cidade" class="inputGrande" placeholder="Ex.: Vitória" required value="<?= old('cidade') ?>">
						</div>
					</div>
					<div class="col-4">
						<div class="grupoForm">
							<label for="estadoArtesa">Estado</label>
							<select id="estadoArtesa" name="estado" class="inputGrande">
								<option value="ES" <?= old('estado') == 'ES' ? 'selected' : '' ?>>ES</option>
								<option value="SP" <?= old('estado') == 'SP' ? 'selected' : '' ?>>SP</option>
								<option value="RJ" <?= old('estado') == 'RJ' ? 'selected' : '' ?>>RJ</option>
								<option value="MG" <?= old('estado') == 'MG' ? 'selected' : '' ?>>MG</option>
								<option value="outro" <?= old('estado') == 'outro' ? 'selected' : '' ?>>Outro</option>
							</select>
						</div>
					</div>
				</div>
			</fieldset>

<fieldset class="p-4 mb-4 bg-light border rounded border-fendi">
				<legend class="fs-5 fw-bold d-flex align-items-center gap-2 px-2 mb-3 text-cafe float-none w-auto fs-jost">
					<span class="material-symbols-outlined fs-5">lock</span>
					Acesso à Conta
				</legend>

				<div class="grupoForm mb-3">
					<label for="emailArtesa">E-mail <span class="badgeObrigatorio">Obrigatório</span></label>
					<input type="email" id="emailArtesa" name="email" class="inputGrande" placeholder="seu@email.com" required inputmode="email" autocomplete="email" value="<?= old('email') ?>">
				</div>

				<div class="grupoForm mb-3">
					<label for="senhaArtesa">Senha <span class="badgeObrigatorio">Obrigatório</span></label>
					<input type="password" id="senhaArtesa" name="senha" class="inputGrande" placeholder="Mínimo 6 caracteres" required minlength="6" autocomplete="new-password">
				</div>

				<div class="grupoForm mb-0">
					<label for="confirmaSenhaArtesa">Confirmar Senha</label>
					<input type="password" id="confirmaSenhaArtesa" name="confirmarSenha" class="inputGrande" placeholder="Repita a sua senha" minlength="6" autocomplete="new-password">
				</div>
			</fieldset>

			<button type="submit" class="btnSubmit artesa" id="btnCadastrarArtesa">
				Criar minha conta de artesã →
			</button>

			</form>

			<p class="linkRodapeForm">
				Já tem uma conta?
				<a href="<?= base_url('login') ?>">Entrar agora</a>
			</p>

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
