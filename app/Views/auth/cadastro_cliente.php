<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Crie sua conta de cliente no Crochettei e compre peças de crochê artesanal feitas com amor.">
	<title>Crochettei — Cadastro de Cliente</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

<link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/forms.css') ?>">
</head>

<body class="paginaFormulario paginaCliente">

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
				<div class="iconeFormulario cliente">
					<span class="material-symbols-outlined">shopping_bag</span>
				</div>
				<h2>Cadastro de Cliente</h2>
				<p>Crie sua conta e descubra peças de crochê artesanais únicas.</p>
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

<div class="notaRN cliente">
				<span class="material-symbols-outlined">info</span>
				<p>
					<strong>Por que pedimos esses dados?</strong>
					Precisamos do seu CPF e endereço para confirmar sua identidade, garantir a
					segurança da compra e entregar seus pedidos corretamente.
				</p>
			</div>

			<form action="<?= base_url('cadastro/cliente') ?>" method="post" class="mt-4" id="formCadastroCliente">
			<?= csrf_field() ?>

<fieldset class="p-4 mb-4 bg-light border rounded border-fendi">
				<legend class="fs-5 fw-bold d-flex align-items-center gap-2 px-2 mb-3 text-cafe float-none w-auto fs-jost">
					<span class="material-symbols-outlined fs-5">person</span>
					Dados Pessoais
				</legend>

				<div class="grupoForm mb-3">
					<label for="nomeCliente">
						Nome completo
						<span class="badgeObrigatorio">Obrigatório</span>
					</label>
					<input
						type="text"
						id="nomeCliente"
						name="nome"
						class="inputGrande"
						placeholder="Ex.: Ana Paula Rocha"
						required
						autocomplete="name"
						value="<?= old('nome') ?>">
				</div>

				<div class="grupoForm mb-3">
					<label for="cpfCliente">
						CPF
						<span class="badgeObrigatorio">Obrigatório</span>
					</label>
					<input
						type="text"
						id="cpfCliente"
						name="cpf"
						class="inputGrande"
						placeholder="Digite apenas os 11 números"
						pattern="[0-9]{11}"
						maxlength="11"
						required
						inputmode="numeric"
						value="<?= old('cpf') ?>">
					<div class="form-text text-fendi small">Digite apenas números, sem pontos ou traços.</div>
				</div>

				<div class="grupoForm mb-0">
					<label for="telefoneCliente">Telefone / WhatsApp</label>
					<input
						type="tel"
						id="telefoneCliente"
						name="telefone"
						class="inputGrande"
						placeholder="(27) 9 0000-0000"
						inputmode="tel"
						value="<?= old('telefone') ?>">
				</div>
			</fieldset>

<fieldset class="p-4 mb-4 bg-light border rounded border-fendi">
				<legend class="fs-5 fw-bold d-flex align-items-center gap-2 px-2 mb-3 text-cafe float-none w-auto fs-jost">
					<span class="material-symbols-outlined fs-5">local_shipping</span>
					Endereço de Entrega
				</legend>

				<div class="grupoForm mb-3">
					<label for="cepCliente">
						CEP
						<span class="badgeObrigatorio">Obrigatório</span>
					</label>
					<input type="text" id="cepCliente" name="cep" class="inputGrande" placeholder="00000-000" maxlength="9" required inputmode="numeric" value="<?= old('cep') ?>">
				</div>

				<div class="row g-2 mb-3">
					<div class="col-8">
						<div class="grupoForm">
							<label for="ruaCliente">Rua / Avenida <span class="badgeObrigatorio">Obrigatório</span></label>
							<input type="text" id="ruaCliente" name="rua" class="inputGrande" placeholder="Nome da sua rua" required value="<?= old('rua') ?>">
						</div>
					</div>
					<div class="col-4">
						<div class="grupoForm">
							<label for="numeroCliente">Número <span class="badgeObrigatorio">Obrigatório</span></label>
							<input type="text" id="numeroCliente" name="numero" class="inputGrande" placeholder="Ex.: 42" required inputmode="numeric" value="<?= old('numero') ?>">
						</div>
					</div>
				</div>

				<div class="grupoForm mb-3">
					<label for="complementoCliente">Complemento</label>
					<input type="text" id="complementoCliente" name="complemento" class="inputGrande" placeholder="Apto, Bloco, Casa... (opcional)" value="<?= old('complemento') ?>">
				</div>

				<div class="grupoForm mb-3">
					<label for="bairroCliente">Bairro <span class="badgeObrigatorio">Obrigatório</span></label>
					<input type="text" id="bairroCliente" name="bairro" class="inputGrande" placeholder="Nome do seu bairro" required value="<?= old('bairro') ?>">
				</div>

				<div class="row g-2 mb-0">
					<div class="col-8">
						<div class="grupoForm">
							<label for="cidadeCliente">Cidade <span class="badgeObrigatorio">Obrigatório</span></label>
							<input type="text" id="cidadeCliente" name="cidade" class="inputGrande" placeholder="Ex.: Vitória" required value="<?= old('cidade') ?>">
						</div>
					</div>
					<div class="col-4">
						<div class="grupoForm">
							<label for="estadoCliente">Estado</label>
							<select id="estadoCliente" name="estado" class="inputGrande">
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
					<label for="emailCliente">E-mail <span class="badgeObrigatorio">Obrigatório</span></label>
					<input type="email" id="emailCliente" name="email" class="inputGrande" placeholder="seu@email.com" required inputmode="email" autocomplete="email" value="<?= old('email') ?>">
				</div>

				<div class="grupoForm mb-3">
					<label for="senhaCliente">Senha <span class="badgeObrigatorio">Obrigatório</span></label>
					<input type="password" id="senhaCliente" name="senha" class="inputGrande" placeholder="Mínimo 6 caracteres" required minlength="6" autocomplete="new-password">
				</div>

				<div class="grupoForm mb-0">
					<label for="confirmaSenhaCliente">Confirmar Senha</label>
					<input type="password" id="confirmaSenhaCliente" name="confirmarSenha" class="inputGrande" placeholder="Repita a sua senha" minlength="6" autocomplete="new-password">
				</div>
			</fieldset>

			<button type="submit" class="btnSubmit cliente" id="btnCadastrarCliente">
				Criar minha conta de cliente →
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
