<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description"
		content="Acompanhe seus pedidos no Crochettei. Veja o status de cada venda e fale com seus clientes.">
	<title>Crochettei — Meus Pedidos</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

	<link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/pedidos.css') ?>">

	<style>
		/* Estilo auxiliar para pequenos formulários em linha dentro dos botões */
		.formAcaoEmLinha {
			display: inline-block;
			margin: 0;
			padding: 0;
		}
		.btnAcao {
			border: none;
			cursor: pointer;
		}
	</style>
</head>

<body class="paginaDashboard">

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
			<button onclick="window.location.href='<?= base_url('logout') ?>'" id="btnHeaderSair" aria-label="Sair da conta">
				<span class="material-symbols-outlined align-middle" style="font-size:1em; margin-right:0.3em;">logout</span>
				Sair
			</button>
		</div>
	</header>

	<?php if (session()->getFlashdata('sucesso')): ?>
	<div class="alert alert-success mx-3 mt-3" role="alert"><?= esc(session()->getFlashdata('sucesso')) ?></div>
	<?php endif; ?>
	<?php if (session()->getFlashdata('erro')): ?>
	<div class="alert alert-danger mx-3 mt-3" role="alert"><?= esc(session()->getFlashdata('erro')) ?></div>
	<?php endif; ?>

	<div class="dashboardHeader">
		<h2>
			<span class="material-symbols-outlined align-middle me-2" style="font-size: 1em;">inventory_2</span>
			Meus Pedidos
		</h2>
		<p>Aqui estão todas as peças que seus clientes pediram para você.</p>
	</div>

	<main class="container py-4 px-3 px-md-4">

		<div class="legendaBadges mb-4">
			<p class="legendaTitulo mb-2">O que significa cada cor?</p>
			<div class="legendaItens">
				<div class="legendaItem">
					<span class="badgeStatus badge-amarelo">Aguardando</span>
					<span class="legendaTexto">Aguardando Pagamento/Aceite</span>
				</div>
				<div class="legendaItem">
					<span class="badgeStatus badge-amarelo">Em Produção</span>
					<span class="legendaTexto">Você está fazendo</span>
				</div>
				<div class="legendaItem">
					<span class="badgeStatus badge-azul">Coleta / Envios</span>
					<span class="legendaTexto">Pronto para Correios / Enviado</span>
				</div>
				<div class="legendaItem">
					<span class="badgeStatus badge-verde">Concluído</span>
					<span class="legendaTexto">Pedido entregue</span>
				</div>
			</div>
		</div>

		<?php if (empty($pedidos)): ?>
		<div class="text-center py-5">
			<span class="material-symbols-outlined" style="font-size:4rem; color:#b5a89e;">inventory_2</span>
			<p class="text-fendi mt-3 fs-5">Nenhum pedido encontrado.</p>
		</div>
		<?php else: ?>

		<?php foreach ($pedidos as $pedido): ?>
		<?php 
			$isCancelado = ($pedido->status_entrega === 'cancelado');
			$isEntregue = ($pedido->status_entrega === 'entregue');
			$finalizadoCss = ($isCancelado || $isEntregue) ? 'cardPedidoConcluido' : '';
		?>

		<article class="cardPedido <?= $finalizadoCss ?>" id="pedido-<?= $pedido->id ?>" aria-label="Pedido número <?= $pedido->id ?>">

			<div class="cardPedidoCabecalho">
				<div>
					<span class="pedidoNumero">Pedido #<?= $pedido->id ?></span>
					<span class="pedidoData"><?= date('d/m/Y', strtotime($pedido->created_at)) ?></span>
				</div>
				<div class="pedidoBadges">
					<span class="badgeStatus <?= $pedido->status_pagamento === 'aprovado' ? 'badge-verde' : 'badge-amarelo' ?>">
						<span class="material-symbols-outlined align-middle" style="font-size:0.9em;">payments</span>
						<?= $pedido->status_pagamento === 'aprovado' ? 'Pago' : 'Pagamento Pendente' ?>
					</span>
					
					<?php if ($pedido->status_pagamento === 'aprovado' && $pedido->status_entrega === 'em_producao'): ?>
						<span class="badgeStatus badge-amarelo">Em Produção</span>
					<?php elseif ($pedido->status_pagamento === 'aprovado' && $pedido->status_entrega === 'aguardando_coleta'): ?>
						<span class="badgeStatus badge-azul">Aguardando Coleta</span>
					<?php elseif ($pedido->status_pagamento === 'aprovado' && $pedido->status_entrega === 'enviado'): ?>
						<span class="badgeStatus badge-azul">Enviado</span>
					<?php elseif ($pedido->status_entrega === 'entregue'): ?>
						<span class="badgeStatus badge-verde">Entregue</span>
					<?php elseif ($pedido->status_entrega === 'cancelado'): ?>
						<span class="badgeStatus" style="background-color:#f5c8c8; color:#932121;">Cancelado</span>
					<?php else: ?>
						<span class="badgeStatus badge-amarelo">Aguardando Aceite</span>
					<?php endif; ?>
				</div>
			</div>

			<div class="textoHumanizado" <?= $isEntregue ? 'style="background-color: #f8fcf8;"' : '' ?>>
				<?php if ($isCancelado): ?>
					<span class="material-symbols-outlined" style="color: #932121;">cancel</span>
					<span>Este pedido foi <strong>cancelado</strong>.</span>
				<?php elseif ($isEntregue): ?>
					<span class="material-symbols-outlined" style="color: #4a6741;">celebration</span>
					<span>Pedido <strong>concluído com sucesso!</strong> O cliente recebeu a peça.</span>
				<?php else: ?>
					<span class="material-symbols-outlined">check_circle</span>
					<span>
						O pedido do cliente <strong><?= esc($pedido->cliente_nome ?? 'Cliente') ?></strong> 
						está <?= str_replace('_', ' ', $pedido->status_entrega) ?>.
					</span>
				<?php endif; ?>
			</div>

			<div class="cardPedidoCorpo">
				<div class="pedidoDetalhe">
					<span class="material-symbols-outlined text-fendi">person</span>
					<div>
						<strong>Cliente</strong>
						<p><?= esc($pedido->cliente_nome ?? 'Não informado') ?></p>
					</div>
				</div>
				<div class="pedidoDetalhe">
					<span class="material-symbols-outlined text-fendi">payments</span>
					<div>
						<strong>Valor Total</strong>
						<p class="text-vinho fw-bold" style="font-size:1.05em;">R$ <?= number_format($pedido->valor_total, 2, ',', '.') ?></p>
					</div>
				</div>
				<div class="pedidoDetalhe">
					<span class="material-symbols-outlined text-fendi">location_on</span>
					<div>
						<strong>Entregar em</strong>
					<p><?= esc($pedido->logradouro . ', ' . $pedido->numero . ' — ' . $pedido->bairro . ', ' . $pedido->cidade . '/' . $pedido->estado) ?></p>
					</div>
				</div>
			</div>

			<?php if (!$isCancelado && !$isEntregue): ?>
			<div class="cardPedidoAcoes">
				
				<?php if ($pedido->status_pagamento === 'pendente' && $pedido->metodo_pagamento === 'pix'): ?>
					<form action="<?= base_url('artesa/pedidos/pagamento/' . $pedido->id) ?>" method="post" class="formAcaoEmLinha">
						<?= csrf_field() ?>
						<button type="submit" class="btnAcao btnAceitarPedido" aria-label="Confirmar pagamento do pedido">
							<span class="material-symbols-outlined">check_circle</span>
							Confirmar Pagamento
						</button>
					</form>
				<?php endif; ?>

				<?php if ($pedido->status_entrega === 'em_producao'): ?>
					<form action="<?= base_url('artesa/pedidos/status/' . $pedido->id) ?>" method="post" class="formAcaoEmLinha">
						<?= csrf_field() ?>
						<input type="hidden" name="status_entrega" value="aguardando_coleta">
						<button type="submit" class="btnAcao btnEnviar" aria-label="Marcar como aguardando coleta">
							<span class="material-symbols-outlined">local_shipping</span>
							Aguardando Coleta
						</button>
					</form>
				<?php elseif ($pedido->status_entrega === 'aguardando_coleta'): ?>
					<form action="<?= base_url('artesa/pedidos/status/' . $pedido->id) ?>" method="post" class="formAcaoEmLinha">
						<?= csrf_field() ?>
						<input type="hidden" name="status_entrega" value="enviado">
						<button type="submit" class="btnAcao btnEnviar" aria-label="Marcar como enviado aos correios">
							<span class="material-symbols-outlined">local_shipping</span>
							Marcar como Enviado
						</button>
					</form>
				<?php elseif ($pedido->status_entrega === 'enviado'): ?>
					<form action="<?= base_url('artesa/pedidos/status/' . $pedido->id) ?>" method="post" class="formAcaoEmLinha">
						<?= csrf_field() ?>
						<input type="hidden" name="status_entrega" value="entregue">
						<button type="submit" class="btnAcao btnEnviar" style="background-color:#4a6741;" aria-label="Confirmar entrega do pedido">
							<span class="material-symbols-outlined">task_alt</span>
							Confirmar Entrega
						</button>
					</form>
				<?php endif; ?>

				<?php if ($pedido->cliente_telefone): ?>
				<a href="https://wa.me/55<?= preg_replace('/\D/', '', $pedido->cliente_telefone) ?>?text=Ol%C3%A1%20<?= urlencode($pedido->cliente_nome ?? '') ?>!%20Mensagem%20sobre%20seu%20pedido%20%23<?= $pedido->id ?>."
					target="_blank"
					class="btnAcao btnWhatsApp"
					aria-label="Falar com cliente pelo WhatsApp">
					<svg class="iconWhatsApp" viewBox="0 0 24 24" aria-hidden="true">
						<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
						<path d="M12 0C5.373 0 0 5.373 0 12c0 2.125.558 4.119 1.533 5.847L.057 23.857a.75.75 0 0 0 .918.942l6.186-1.621A11.945 11.945 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.885 0-3.65-.518-5.163-1.42l-.37-.218-3.828 1.003.957-3.715-.24-.384A9.965 9.965 0 0 1 2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/>
					</svg>
					Falar no WhatsApp
				</a>
				<?php endif; ?>

				<button class="btnAcao btnCancelar" id="btnCancelarPedido-<?= $pedido->id ?>"
					onclick="abrirCancelamento('<?= $pedido->id ?>')"
					aria-label="Cancelar pedido <?= $pedido->id ?>">
					<span class="material-symbols-outlined">cancel</span>
					Cancelar Pedido
				</button>
			</div>

			<div class="zonaCancelamento" id="zona-cancelamento-<?= $pedido->id ?>" role="alert" aria-live="assertive">
				<form action="<?= base_url('artesa/pedidos/status/' . $pedido->id) ?>" method="post" style="width:100%;">
					<?= csrf_field() ?>
					<input type="hidden" name="status_entrega" value="cancelado">
					
					<div class="zonaCancelamentoTexto">
						<span class="material-symbols-outlined">warning</span>
						Tem certeza que quer cancelar? O cliente será notificado.
					</div>
					<div class="zonaCancelamentoBotoes">
						<button type="button" class="btnDesfazer" onclick="fecharCancelamento('<?= $pedido->id ?>')">
							<span class="material-symbols-outlined">undo</span>
							Não, Voltar Atrás
						</button>
						<button type="submit" class="btnConfirmarCancelamento" id="confirmar-cancelamento-<?= $pedido->id ?>">
							<span class="material-symbols-outlined">delete_forever</span>
							Sim, Cancelar
						</button>
						<span class="countdownCancelamento" id="countdown-<?= $pedido->id ?>"></span>
					</div>
				</form>
			</div>
			<?php endif; ?>

		</article>
		<?php endforeach; ?>

		<?php endif; ?>

	</main>

	<footer class="footerBottom p-4 mt-auto bg-offwhite border-top border-fendi">
		<p>Feito com <span class="material-symbols-outlined footerHeart text-vinho">favorite</span> por Crochettei © 2026</p>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmxc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
		crossorigin="anonymous"></script>

	<script>
		const countdowns = {};

		function abrirCancelamento(pedidoId) {
			const zona = document.getElementById(`zona-cancelamento-${pedidoId}`);
			const countdownEl = document.getElementById(`countdown-${pedidoId}`);
			const btnCancelar = document.getElementById(`btnCancelarPedido-${pedidoId}`);

			if (!zona) return;
			zona.classList.add('ativo');
			if (btnCancelar) btnCancelar.style.display = 'none';

			let segundos = 10;
			const btnConfirmar = document.getElementById(`confirmar-cancelamento-${pedidoId}`);
			if (btnConfirmar) btnConfirmar.disabled = true;
			if (countdownEl) countdownEl.textContent = `(aguarde ${segundos}s)`;

			countdowns[pedidoId] = setInterval(() => {
				segundos--;
				if (segundos <= 0) {
					clearInterval(countdowns[pedidoId]);
					if (btnConfirmar) btnConfirmar.disabled = false;
					if (countdownEl) countdownEl.textContent = '';
				} else {
					if (countdownEl) countdownEl.textContent = `(aguarde ${segundos}s)`;
				}
			}, 1000);
		}

		function fecharCancelamento(pedidoId) {
			const zona = document.getElementById(`zona-cancelamento-${pedidoId}`);
			const btnCancelar = document.getElementById(`btnCancelarPedido-${pedidoId}`);
			if (zona) zona.classList.remove('ativo');
			if (btnCancelar) btnCancelar.style.display = '';
			if (countdowns[pedidoId]) {
				clearInterval(countdowns[pedidoId]);
				delete countdowns[pedidoId];
			}
		}
	</script>

</body>

</html>
