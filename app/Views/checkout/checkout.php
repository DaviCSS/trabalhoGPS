<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Finalize sua compra no Crochettei com segurança. Informe seu endereço e CPF para concluir o pedido.">
	<title>Crochettei — Checkout Seguro</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

	<link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/forms.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/checkout.css') ?>">
</head>

<body class="paginaCheckout">

<!-- Esta é a tela final de pagamento onde o cliente preenche os dados do cartão/pix -->
	<header class="navBar">
		<div class="logo">
			<h1>Crochettei</h1>
		</div>
		<nav class="botoes">
			<?php if (session()->get('logado')): ?>
			<button onclick="window.location.href='<?= base_url('cliente/dashboard') ?>'">Meu Painel</button>
			<?php endif; ?>
			<button onclick="window.location.href='<?= base_url('carrinho') ?>'">
				<span class="material-symbols-outlined align-middle" style="font-size: 1.2em;">arrow_back</span>
				Voltar ao Carrinho
			</button>
		</nav>
		<div class="botLog">
			<button onclick="window.location.href='<?= base_url('logout') ?>'" id="btnHeaderSair">Sair</button>
		</div>
	</header>

	<?php if (session()->getFlashdata('erro')): ?>
	<div class="alert alert-danger mx-3 mt-3" role="alert"><?= esc(session()->getFlashdata('erro')) ?></div>
	<?php endif; ?>

	<div class="dashboardHeader text-center py-4 bg-white border-bottom border-fendi">
		<h2 class="fs-jost">Finalização do Pedido</h2>
		<p class="text-fendi mb-4">Siga os passos abaixo para concluir sua compra com segurança.</p>
		
		<!-- Barra de Progresso Wizard -->
		<div class="wizard-progress container max-w-700">
			<div class="step-indicator active" id="indicator-step1">
				<div class="step-circle">1</div>
				<span>Resumo</span>
			</div>
			<div class="step-line" id="line-step1"></div>
			<div class="step-indicator" id="indicator-step2">
				<div class="step-circle">2</div>
				<span>Identificação</span>
			</div>
			<div class="step-line" id="line-step2"></div>
			<div class="step-indicator" id="indicator-step3">
				<div class="step-circle">3</div>
				<span>Pagamento</span>
			</div>
		</div>
	</div>

	<main class="container py-5 max-w-800">
		<form id="formCheckout" action="<?= base_url('checkout/finalizar') ?>" method="post" novalidate>
			<?= csrf_field() ?>

			<!-- ETAPA 1: RESUMO DO PEDIDO -->
			<div id="step1" class="wizard-step">
				<div class="checkoutCard mb-4">
					<h3 class="checkoutCardTitulo">
						<span class="material-symbols-outlined align-middle me-2">shopping_cart</span>
						Confirme os itens do seu carrinho
					</h3>
					
					<div class="resumoItemLista mb-4">
					<?php foreach ($itens as $item): ?>
						<div class="resumoItemLinha d-flex justify-content-between align-items-center py-2 border-bottom">
							<div>
								<span class="fw-bold d-block"><?= esc($item['nome']) ?></span>
								<span class="text-fendi small">Qtd: <?= (int)$item['quantidade'] ?></span>
							</div>
							<span class="fw-bold text-cafe">R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></span>
						</div>
					<?php endforeach; ?>
					</div>
					
					<div class="resumoTotalCheck d-flex justify-content-between fs-4 fw-bold text-vinho">
						<span>Total</span>
						<span>R$ <?= number_format($total, 2, ',', '.') ?></span>
					</div>
				</div>
				<div class="d-flex justify-content-end">
					<button type="button" class="btnAcaoPrincipal" onclick="nextStep(2)">Continuar <span class="material-symbols-outlined align-middle ms-1">arrow_forward</span></button>
				</div>
			</div>

			<!-- ETAPA 2: IDENTIFICAÇÃO E ENTREGA -->
			<div id="step2" class="wizard-step d-none">
				<div class="checkoutCard mb-4">
					<h3 class="checkoutCardTitulo">
						<span class="material-symbols-outlined align-middle me-2">badge</span>
						Seus Dados
					</h3>
					<div class="row g-3 mb-4">
						<div class="col-12">
							<div class="grupoForm">
								<label for="nomeCompleto">Nome Completo <span class="badgeObrigatorio">Obrigatório</span></label>
								<input type="text" id="nomeCompleto" name="nome_completo" class="inputGrande" placeholder="Como está no seu documento" required value="<?= esc($usuario->nome ?? '') ?>">
								<div class="invalid-feedback">Por favor, preencha seu nome.</div>
							</div>
						</div>
						<div class="col-12 col-sm-6">
							<div class="grupoForm">
								<label for="cpfCheckout">CPF <span class="badgeObrigatorio">Obrigatório</span></label>
								<input type="text" id="cpfCheckout" name="cpf" class="inputGrande" placeholder="11 números" pattern="[0-9]{11}" maxlength="11" required value="<?= esc($usuario->cpf ?? '') ?>">
								<div class="invalid-feedback">Digite um CPF válido com 11 números.</div>
							</div>
						</div>
						<div class="col-12 col-sm-6">
							<div class="grupoForm">
								<label for="telefoneCheckout">Telefone / WhatsApp <span class="badgeObrigatorio">Obrigatório</span></label>
								<input type="tel" id="telefoneCheckout" name="telefone" class="inputGrande" placeholder="(99) 99999-9999" required value="<?= esc($usuario->telefone ?? '') ?>">
								<div class="invalid-feedback">Por favor, preencha seu telefone.</div>
							</div>
						</div>
					</div>

					<h3 class="checkoutCardTitulo mt-4 pt-3 border-top">
						<span class="material-symbols-outlined align-middle me-2">location_on</span>
						Endereço de Entrega
					</h3>
					<div class="grupoForm">
						<label for="enderecoEntrega">Endereço Completo <span class="badgeObrigatorio">Obrigatório</span></label>
						<p class="text-fendi small mb-2">Edite o endereço abaixo caso deseje receber em outro local.</p>
						<textarea id="enderecoEntrega" name="endereco_entrega" class="inputGrande" rows="3" required placeholder="Ex: Rua das Flores, 123, Apto 45 - Centro, Vitória - ES, CEP 29000-000"><?= esc($usuario->endereco_completo ?? '') ?></textarea>
						<div class="invalid-feedback">O endereço de entrega é obrigatório.</div>
					</div>
				</div>
				<div class="d-flex justify-content-between">
					<button type="button" class="btnFiltro" onclick="prevStep(1)"><span class="material-symbols-outlined align-middle me-1">arrow_back</span> Voltar</button>
					<button type="button" class="btnAcaoPrincipal" onclick="validaETapa2()">Ir para Pagamento <span class="material-symbols-outlined align-middle ms-1">arrow_forward</span></button>
				</div>
			</div>

			<!-- ETAPA 3: PAGAMENTO -->
			<div id="step3" class="wizard-step d-none">
				<div class="checkoutCard mb-4">
					<h3 class="checkoutCardTitulo">
						<span class="material-symbols-outlined align-middle me-2">payments</span>
						Forma de Pagamento
					</h3>

					<div class="opcoesPagamento">
						<div class="opcaoPagamento">
							<input type="radio" id="pagPix" name="pagamento" value="pix" checked>
							<label for="pagPix" class="labelPagamento">
								<div class="iconePagamento" style="background-color: #eaf5ea;">
									<span class="material-symbols-outlined text-oliva">qr_code</span>
								</div>
								<div>
									<strong>Pix</strong>
									<p class="mb-0">Aprovação imediata. Sem taxas extras.</p>
								</div>
								<span class="badgeDesconto ms-auto">Mais rápido</span>
							</label>
						</div>

						<div class="opcaoPagamento mt-3">
							<input type="radio" id="pagCartao" name="pagamento" value="cartao">
							<label for="pagCartao" class="labelPagamento">
								<div class="iconePagamento" style="background-color: #fdf6f6;">
									<span class="material-symbols-outlined text-vinho">credit_card</span>
								</div>
								<div>
									<strong>Cartão de Crédito</strong>
									<p class="mb-0">Em até 3x sem juros.</p>
								</div>
							</label>
						</div>
					</div>
				</div>

				<div class="resumoPedidoCheckout bg-offwhite p-4 rounded-4 mb-4 text-center">
					<span class="d-block text-fendi mb-2">Total a Pagar</span>
					<span class="fs-2 fw-bold text-vinho">R$ <?= number_format($total, 2, ',', '.') ?></span>
				</div>

				<div class="d-flex justify-content-between align-items-center">
					<button type="button" class="btnFiltro" onclick="prevStep(2)"><span class="material-symbols-outlined align-middle me-1">arrow_back</span> Voltar</button>
					<button type="submit" class="btnFinalizarPedido m-0">
						<span class="material-symbols-outlined align-middle me-2">check_circle</span>
						Finalizar Compra
					</button>
				</div>
				<p class="mensagemSeguranca text-center mt-4">
					<span class="material-symbols-outlined align-middle text-oliva" style="font-size: 1em;">shield</span>
					Seus dados são protegidos e não serão compartilhados.
				</p>
			</div>

		</form>
	</main>

	<footer class="footerBottom p-4 mt-auto bg-offwhite border-top border-fendi text-center">
		<p>Feito com <span class="material-symbols-outlined footerHeart text-vinho">favorite</span> por Crochettei © 2026</p>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		function nextStep(step) {
			document.querySelectorAll('.wizard-step').forEach(el => el.classList.add('d-none'));
			document.getElementById('step' + step).classList.remove('d-none');
			
			// Atualizar Progress Bar
			for (let i = 1; i <= 3; i++) {
				let ind = document.getElementById('indicator-step' + i);
				let line = document.getElementById('line-step' + i);
				if (i <= step) {
					if(ind) ind.classList.add('active');
					if(line && i < step) line.classList.add('active');
				} else {
					if(ind) ind.classList.remove('active');
					if(line) line.classList.remove('active');
				}
			}
			window.scrollTo(0, 0);
		}

		function prevStep(step) {
			nextStep(step);
		}

		function validaETapa2() {
			let nome = document.getElementById('nomeCompleto');
			let cpf = document.getElementById('cpfCheckout');
			let tel = document.getElementById('telefoneCheckout');
			let end = document.getElementById('enderecoEntrega');
			
			let valido = true;

			// Reset de validação
			[nome, cpf, tel, end].forEach(el => el.classList.remove('is-invalid'));

			if (!nome.value.trim()) { nome.classList.add('is-invalid'); valido = false; }
			if (!cpf.value.trim() || !/^[0-9]{11}$/.test(cpf.value)) { cpf.classList.add('is-invalid'); valido = false; }
			if (!tel.value.trim()) { tel.classList.add('is-invalid'); valido = false; }
			if (!end.value.trim()) { end.classList.add('is-invalid'); valido = false; }

			if (valido) {
				nextStep(3);
			} else {
				// Foca no primeiro campo inválido
				let firstInvalid = document.querySelector('.is-invalid');
				if (firstInvalid) firstInvalid.focus();
			}
		}
	</script>
</body>

</html>
