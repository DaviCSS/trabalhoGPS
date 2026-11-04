<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Entre na sua conta Crochettei e acesse suas vendas, pedidos e produtos.">
	<title>Crochettei — Entrar na conta</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

<link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
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
			<button onclick="window.location.href='<?= base_url('cadastro') ?>'" id="btnHeaderCadastrar">Criar conta</button>
		</div>
	</header>

<div class="areaLogin">

<?php if (session()->getFlashdata('erro') || session()->getFlashdata('aviso') || session()->getFlashdata('sucesso')): ?>
<div class="toast-container position-fixed top-0 end-0 p-4" style="z-index: 1100;">
    <?php foreach (['erro' => 'text-bg-danger', 'aviso' => 'text-bg-warning', 'sucesso' => 'text-bg-success'] as $tipo => $bgClass): ?>
        <?php if (session()->getFlashdata($tipo)): ?>
        <div class="toast align-items-center <?= $bgClass ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body fs-6 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined"><?= $tipo === 'erro' ? 'error' : ($tipo === 'aviso' ? 'warning' : 'check_circle') ?></span>
                    <?= esc(session()->getFlashdata($tipo)) ?>
                </div>
                <button type="button" class="btn-close <?= $tipo === 'aviso' ? '' : 'btn-close-white' ?> me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<aside class="painelMotivacional">
			<div class="logoLogin">Crochettei</div>
			<h2>
				Bem-vinda de volta,<br>
				<strong>artesã.</strong>
			</h2>
			<p>
				Acesse sua conta e veja seus pedidos, produtos e vendas — tudo num só lugar.
			</p>
			<ul class="listaPainel">
				<li>
					<span class="material-symbols-outlined">check_circle</span>
					Logística resolvida para você
				</li>
				<li>
					<span class="material-symbols-outlined">check_circle</span>
					Pagamento direto na sua conta
				</li>
				<li>
					<span class="material-symbols-outlined">check_circle</span>
					Cadastro 100% gratuito
				</li>
			</ul>
		</aside>

<div class="painelFormLogin">
			<div class="cardFormulario">

				<div class="cabecalhoLogin">
					<div class="iconeLogin">
						<span class="material-symbols-outlined">lock_open</span>
					</div>
					<h2>Entrar na conta</h2>
					<p>Digite seu e-mail ou CPF e sua senha.</p>
				</div>

				<form action="<?= base_url('login') ?>" method="post" novalidate id="formLogin">
				<?= csrf_field() ?>

					<div class="grupoForm mb-3">
						<label for="loginIdentificador">E-mail ou CPF</label>
						<input type="text" id="loginIdentificador" name="identificador" class="inputGrande"
							placeholder="seu@email.com ou 000.000.000-00" required autocomplete="username"
							inputmode="email" value="<?= old('identificador') ?>">
					</div>

					<div class="grupoForm mb-3">
						
						<div class="linhaEsqueci">
							<label for="loginSenha">Senha</label>
							<a href="<?= base_url('recuperar-senha') ?>" class="linkEsqueci" id="linkEsqueciSenha">
								Esqueci minha senha
							</a>
						</div>
						<input type="password" id="loginSenha" name="senha" class="inputGrande"
							placeholder="Digite sua senha" required autocomplete="current-password">
					</div>

					<div class="row g-3 mt-4">
						<div class="col-12">
							<button type="submit"
								class="btnEntrar w-100 m-0"
								id="btnEntrar">
								Entrar
							</button>
						</div>
					</div>

				</form>

				<div class="separadorOu mt-4 mb-3">ou</div>

				<div class="grupoCadastro text-center">
					<a href="<?= base_url('cadastro') ?>" id="linkCadastroCliente"
						class="text-fendi small text-decoration-underline">
						Não tenho conta ainda — Criar conta
					</a>
				</div>

			</div>
		</div>

	</div>

<footer class="footerBottom p-4 mt-auto bg-offwhite border-top border-fendi">
		<p>Feito com <span class="material-symbols-outlined footerHeart text-vinho">favorite</span> por Crochettei ©
			2026</p>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmxc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
		crossorigin="anonymous"></script>

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const toastElList = document.querySelectorAll('.toast');
			const toastList = [...toastElList].map(toastEl => new bootstrap.Toast(toastEl, { delay: 5000 }));
			toastList.forEach(toast => toast.show());
		});
	</script>
</body>

</html>
