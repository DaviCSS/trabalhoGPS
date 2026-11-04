<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Painel de Administração Global — Crochettei.">
	<title>Crochettei — Painel do Administrador</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link
		href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap"
		rel="stylesheet">
	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

	<link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
	<!-- Reutilizamos as classes do painelAdmin.css -->
	<link rel="stylesheet" href="<?= base_url('css/painelAdmin.css') ?>">
</head>

<body class="paginaAdmin">

	<!-- CABEÇALHO ESCURO (PRETO CAFÉ) CONFORME SOLICITADO -->
	<header class="navBarAdmin" role="banner" style="background-color: #2b1f1a;">
		<div class="logo">
			<h1>Crochettei</h1>
			<span class="badgeAdmin">Super Admin</span>
		</div>
		<nav class="navAdmin" aria-label="Navegação do painel do ajudante">
			<a href="#secaoVisaoGeral" class="navAdminLink navAdminLinkAtivo">Visão Geral Global</a>
			<a href="#secaoRelatorios" class="navAdminLink">Desempenho</a>
			<a href="#secaoPedidos" class="navAdminLink">Fila de Pedidos</a>
		</nav>
		<div class="navAdminPerfil">
			<span class="material-symbols-outlined text-fendi">shield_person</span>
			<span class="adminNome"><?= esc(session()->get('user_nome')) ?></span>
			<button onclick="window.location.href='<?= base_url('logout') ?>'" id="btnAdminSair"
				class="btn btn-outline-light btn-sm rounded-pill ms-2" aria-label="Sair do painel de administração">Sair</button>
		</div>
	</header>

	<div class="bannerAjudante" role="region" aria-label="Área restrita de administração" style="background: linear-gradient(135deg, #1a1210 0%, #000000 100%);">
		<div class="container">
			<div class="bannerAjudanteTopo">
				<div class="bannerAjudanteIcone">
					<span class="material-symbols-outlined">shield</span>
					<div class="bannerAjudanteTexto">
						<h2>Centro de Comando / Gestão Global</h2>
						<p>Visão gerencial de toda a plataforma Crochettei. Ações realizadas aqui afetam os perfis das artesãs.</p>
					</div>
				</div>

				<!-- O Admin não tem painel de artesã para voltar, vamos colocar um botão para o Catálogo -->
				<a href="<?= base_url('catalogo') ?>" class="btnVoltarArtesa" aria-label="Acessar o catálogo público">
					<span class="material-symbols-outlined">storefront</span>
					Ver Catálogo Público
				</a>
			</div>

			<?php if (session()->getFlashdata('sucesso')): ?>
				<div class="alert alert-success mt-3 mb-0" role="alert"><?= esc(session()->getFlashdata('sucesso')) ?></div>
			<?php endif; ?>
			<?php if (session()->getFlashdata('erro')): ?>
				<div class="alert alert-danger mt-3 mb-0" role="alert"><?= esc(session()->getFlashdata('erro')) ?></div>
			<?php endif; ?>

		</div>
	</div>

	<main class="container py-5">

		<section id="secaoVisaoGeral" class="mb-5" aria-labelledby="tituloVisaoGeral">

			<h2 class="secaoTituloAdmin" id="tituloVisaoGeral">
				<span class="material-symbols-outlined align-middle me-2">public</span>
				Desempenho Geral da Plataforma
			</h2>

			<div class="row g-3">

				<div class="col-12 col-sm-6 col-xl-3">
					<div class="cardAdmin cardAdmin-artesa" role="article">
						<div class="cardAdminIcone">
							<span class="material-symbols-outlined">palette</span>
						</div>
						<div class="cardAdminDados">
							<span class="cardAdminNumero"><?= $totalProdutos ?></span>
							<span class="cardAdminRotulo">Produtos na Vitrine</span>
							<span class="cardAdminDetalhe">
								<span class="material-symbols-outlined align-middle" style="font-size:0.9em;">storefront</span>
								Ativos agora (Global)
							</span>
						</div>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-xl-3">
					<div class="cardAdmin cardAdmin-vendas" role="article">
						<div class="cardAdminIcone">
							<span class="material-symbols-outlined">payments</span>
						</div>
						<div class="cardAdminDados">
							<span class="cardAdminNumero">R$ <?= number_format($totalFaturado, 2, ',', '.') ?></span>
							<span class="cardAdminRotulo">Faturamento Total</span>
							<span class="cardAdminDetalhe tendencia-alta">
								<span class="material-symbols-outlined align-middle" style="font-size:0.9em;">trending_up</span>
								Todas as vendas
							</span>
						</div>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-xl-3">
					<div class="cardAdmin cardAdmin-pedidos" role="article">
						<div class="cardAdminIcone">
							<span class="material-symbols-outlined">inventory_2</span>
						</div>
						<div class="cardAdminDados">
							<span class="cardAdminNumero"><?= $pedidosAtivos ?></span>
							<span class="cardAdminRotulo">Aguardando Pagamento</span>
							<span class="cardAdminDetalhe">
								<span class="material-symbols-outlined align-middle" style="font-size:0.9em;">hourglass_empty</span>
								Ação necessária
							</span>
						</div>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-xl-3">
					<div class="cardAdmin cardAdmin-cliente" role="article">
						<div class="cardAdminIcone">
							<span class="material-symbols-outlined">account_balance_wallet</span>
						</div>
						<div class="cardAdminDados">
							<span class="cardAdminNumero">R$ <?= number_format($saldoAReceber, 2, ',', '.') ?></span>
							<span class="cardAdminRotulo">Volume Pago</span>
							<span class="cardAdminDetalhe">
								<svg width="1.2em" height="1.2em" viewBox="0 0 512 512" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="align-middle" style="margin-top:-2px;"><path d="M141.226 211.536L211.542 141.22a63.504 63.504 0 0 1 89.813 0L371.67 211.536l-32.84 32.84-70.315-70.315a17.067 17.067 0 0 0-24.135 0l-70.314 70.315-32.84-32.84zm159.227 88.928l-70.313 70.314a17.067 17.067 0 0 1-24.136 0l-70.314-70.314-32.84 32.84L173.165 403.62a63.505 63.505 0 0 0 89.813 0l70.315-70.315-32.84-32.84z"/><path d="M409.835 255.45l-38.165-38.165-32.84 32.84 26.097 26.098a17.067 17.067 0 0 1 0 24.135l-26.097 26.098 32.84 32.84 38.165-38.166a63.505 63.505 0 0 0 0-89.812zM102.165 255.45l38.165-38.165 32.84 32.84-26.097 26.098a17.067 17.067 0 0 0 0 24.135l26.097 26.098-32.84 32.84-38.165-38.166a63.505 63.505 0 0 1 0-89.812z"/></svg>
								Pedidos confirmados
							</span>
						</div>
					</div>
				</div>

			</div>
		</section>

		<div class="row g-4">

			<div class="col-12">

				<section id="secaoPedidos" class="adminCard" aria-labelledby="tituloPedidos">
					<div class="adminCardCabecalho">
						<h3 id="tituloPedidos">
							<span class="material-symbols-outlined align-middle me-2">receipt_long</span>
							Fila Global de Pedidos
						</h3>
					</div>

					<?php if (empty($pedidos)): ?>
						<div class="p-4 text-center text-muted">
							Nenhum pedido foi realizado na plataforma ainda.
						</div>
					<?php else: ?>
						<div class="table-responsive">
							<table class="table tabelaAdmin mb-0">
								<thead>
									<tr>
										<th>Pedido</th>
										<th>Cliente</th>
										<th>Artesã (Loja)</th>
										<th>Data</th>
										<th>Valor</th>
										<th>Pagamento</th>
										<th>Entrega</th>
										<th>Ação Global</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($pedidos as $pedido): ?>
										<tr>
											<td><strong>#<?= $pedido->id ?></strong></td>
											<td><?= esc($pedido->cliente_nome) ?></td>
											<td><?= esc($pedido->artesa_nome) ?></td>
											<td><?= date('d/m/Y', strtotime($pedido->created_at)) ?></td>
											<td class="fw-bold text-oliva">R$ <?= number_format($pedido->valor_total, 2, ',', '.') ?></td>
											<td>
												<?php if ($pedido->status_pagamento === 'pago'): ?>
													<span class="badge bg-success">Pago</span>
												<?php else: ?>
													<span class="badge bg-warning text-dark">Pendente</span>
												<?php endif; ?>
											</td>
											<td>
												<?php
												$statusBadge = '';
												switch ($pedido->status_entrega) {
													case 'pendente':
														$statusBadge = '<span class="badge bg-secondary">Aguardando</span>';
														break;
													case 'em_producao':
														$statusBadge = '<span class="badge bg-warning text-dark">Produzindo</span>';
														break;
													case 'aguardando_coleta':
														$statusBadge = '<span class="badge bg-info text-dark">Aguardando Coleta</span>';
														break;
													case 'enviado':
														$statusBadge = '<span class="badge bg-primary">Enviado</span>';
														break;
													case 'entregue':
														$statusBadge = '<span class="badge bg-success">Entregue</span>';
														break;
													case 'cancelado':
														$statusBadge = '<span class="badge bg-danger">Cancelado</span>';
														break;
												}
												echo $statusBadge;
												?>
											</td>
											<td>
												<!-- Efeito Cascata: Se o Admin aprova o pagamento, vai afetar o status geral -->
												<?php if ($pedido->status_pagamento === 'pendente' && $pedido->status_entrega !== 'cancelado'): ?>
													<form action="<?= base_url('admin/pedidos/pagamento/' . $pedido->id) ?>" method="post" class="d-inline">
														<?= csrf_field() ?>
														<button type="submit" class="btn btn-sm btn-success" title="Confirmar Pagamento">
															<span class="material-symbols-outlined align-middle" style="font-size:16px;">check_circle</span> Aprovar Pagamento
														</button>
													</form>
												<?php endif; ?>

												<!-- Efeito Cascata: Cancelamento pelo Suporte (Admin) -->
												<?php if ($pedido->status_entrega !== 'cancelado' && $pedido->status_entrega !== 'entregue'): ?>
													<form action="<?= base_url('admin/pedidos/cancelar/' . $pedido->id) ?>" method="post" class="d-inline">
														<?= csrf_field() ?>
														<button type="submit" class="btn btn-sm btn-outline-danger" title="Cancelar Pedido pela Plataforma" onclick="return confirm('ATENÇÃO: Deseja realmente forçar o cancelamento deste pedido? Isso afetará a artesã e o cliente.')">
															<span class="material-symbols-outlined align-middle" style="font-size:16px;">cancel</span> Forçar Cancelamento
														</button>
													</form>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					<?php endif; ?>
				</section>
			</div>
		</div>

	</main>

	<footer class="footerBottom p-4 mt-auto bg-offwhite border-top border-fendi">
		<p>Feito com <span class="material-symbols-outlined footerHeart text-vinho">favorite</span> por Crochettei © 2026 — Área Restrita: Super Admin</p>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmxc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
		crossorigin="anonymous"></script>
</body>

</html>
