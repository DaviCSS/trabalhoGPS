<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Área de apoio e configurações da conta de artesã no Crochettei — acesso do ajudante.">
	<title>Crochettei — Configurações da Conta</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

	<link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/painelAdmin.css') ?>">

	<style>
		.paginaApoioArtesa {
			background-color: #f8f6f0;
			min-height: 100vh;
			display: flex;
			flex-direction: column;
		}

		.bannerApoioArtesa {
			background: linear-gradient(160deg, #2b1f1a 0%, #1a1210 100%);
			border-bottom: 4px solid #932121;
			padding: 1.8em 1.5em;
		}

		.bannerApoioContainer {
			max-width: 760px;
			margin: 0 auto;
			display: flex;
			flex-direction: column;
			gap: 1.2em;
		}

		.bannerApoioTopo {
			display: flex;
			align-items: flex-start;
			justify-content: space-between;
			gap: 1em;
			flex-wrap: wrap;
		}

		.bannerApoioIdentidade {
			display: flex;
			align-items: center;
			gap: 0.8em;
		}

		.bannerApoioIcone {
			width: 48px;
			height: 48px;
			background-color: rgba(253, 251, 247, 0.1);
			border-radius: 14px;
			display: flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
		}

		.bannerApoioIcone .material-symbols-outlined {
			font-size: 1.6em;
			color: #f5c8c8;
		}

		.bannerApoioTitulo h1 {
			font-family: 'Jost', sans-serif;
			font-size: 1.25em;
			color: #FDFBF7;
			margin: 0 0 0.2em;
			font-weight: 600;
		}

		.bannerApoioTitulo p {
			font-family: 'Quicksand', sans-serif;
			font-size: 0.82em;
			color: #b5a89e;
			margin: 0;
			line-height: 1.4;
		}

		.badgeContaArtesa {
			display: inline-flex;
			align-items: center;
			gap: 0.4em;
			background-color: rgba(147, 33, 33, 0.3);
			border: 1px solid rgba(147, 33, 33, 0.5);
			border-radius: 100px;
			padding: 0.35em 0.9em;
			color: #f5c8c8;
			font-family: 'Quicksand', sans-serif;
			font-size: 0.78em;
			font-weight: 700;
			white-space: nowrap;
			flex-shrink: 0;
		}

		.badgeContaArtesa .material-symbols-outlined {
			font-size: 0.9em;
		}

		.bannerApoioAviso {
			display: flex;
			align-items: flex-start;
			gap: 0.6em;
			background-color: rgba(253, 251, 247, 0.07);
			border: 1px solid rgba(253, 251, 247, 0.15);
			border-radius: 12px;
			padding: 0.7em 1em;
		}

		.bannerApoioAviso .material-symbols-outlined {
			color: #f5c8c8;
			font-size: 1em;
			flex-shrink: 0;
			margin-top: 0.1em;
		}

		.bannerApoioAviso p {
			font-family: 'Quicksand', sans-serif;
			font-size: 0.82em;
			color: #b5a89e;
			margin: 0;
			line-height: 1.5;
		}

		.areaVoltarArtesa {
			max-width: 760px;
			margin: 2em auto 0;
			padding: 0 1.2em;
			width: 100%;
		}

		.btnVoltarPainelArtesa {
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 0.8em;
			width: 100%;
			background-color: #932121;
			color: #FDFBF7;
			border: none;
			border-radius: 20px;
			padding: 1.3em 2em;
			font-family: 'Jost', sans-serif;
			font-size: 1.1em;
			font-weight: 600;
			text-decoration: none;
			cursor: pointer;
			transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
			box-shadow: 0 8px 24px rgba(147, 33, 33, 0.2);
			min-height: 70px;
		}

		.btnVoltarPainelArtesa:hover {
			background-color: #7a1a1a;
			color: #FDFBF7;
			transform: translateY(-2px);
			box-shadow: 0 12px 32px rgba(147, 33, 33, 0.3);
		}

		.btnVoltarPainelArtesa .material-symbols-outlined {
			font-size: 1.4em;
		}

		.areaConfigArtesa {
			max-width: 760px;
			margin: 0 auto;
			padding: 1.5em 1.2em 4em;
			width: 100%;
		}

		.secaoApoioTitulo {
			font-family: 'Jost', sans-serif;
			font-size: 1em;
			font-weight: 600;
			color: #7e6f67;
			text-transform: uppercase;
			letter-spacing: 0.05em;
			margin: 1.8em 0 0.8em;
		}

		.cardConfig {
			background-color: #ffffff;
			border: 1px solid #e2dcd5;
			border-radius: 16px;
			overflow: hidden;
			box-shadow: 0 4px 12px rgba(43, 31, 26, 0.05);
			margin-bottom: 1em;
		}

		.cardConfigItem {
			display: flex;
			align-items: center;
			gap: 1em;
			padding: 1.1em 1.4em;
			border-bottom: 1px solid #f8f6f0;
			min-height: 64px;
			transition: background-color 0.15s ease;
		}

		.cardConfigItem:last-child {
			border-bottom: none;
		}

		.cardConfigItem:hover {
			background-color: #fdfbf7;
		}

		.cardConfigIcone {
			width: 42px;
			height: 42px;
			border-radius: 12px;
			background-color: #f8f6f0;
			display: flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
			color: #7e6f67;
		}

		.cardConfigIcone .material-symbols-outlined {
			font-size: 1.3em;
		}

		.cardConfigTexto {
			flex: 1;
		}

		.cardConfigTexto strong {
			font-family: 'Jost', sans-serif;
			font-size: 0.95em;
			color: #2b1f1a;
			display: block;
			margin-bottom: 0.15em;
		}

		.cardConfigTexto span {
			font-family: 'Quicksand', sans-serif;
			font-size: 0.82em;
			color: #7e6f67;
		}

		.cardConfigAcao {
			display: flex;
			align-items: center;
			gap: 0.5em;
			flex-shrink: 0;
		}

		.badgeEmBreve {
			background-color: #fff3cd;
			color: #856404;
			padding: 0.2em 0.7em;
			border-radius: 100px;
			font-size: 0.72em;
			font-weight: 700;
			white-space: nowrap;
		}

		.badgeAtivo {
			background-color: #d4edda;
			color: #155724;
			padding: 0.2em 0.7em;
			border-radius: 100px;
			font-size: 0.72em;
			font-weight: 700;
		}

		.linkConfigItem {
			color: #932121;
			font-size: 0.82em;
			font-weight: 700;
			text-decoration: none;
		}

		.linkConfigItem:hover {
			text-decoration: underline;
		}

		.setaConfig {
			color: #b5a89e;
			font-size: 1.1em;
		}

		@media (max-width: 576px) {
			.bannerApoioTopo {
				flex-direction: column;
			}
			.badgeContaArtesa {
				align-self: flex-start;
			}
			.btnVoltarPainelArtesa {
				font-size: 1em;
				min-height: 60px;
			}
		}
	</style>
</head>

<body class="paginaApoioArtesa">

	<header class="navBar">
		<div class="logo">
			<h1>Crochettei</h1>
		</div>
		<div class="botLog">
			<button onclick="window.location.href='<?= base_url('logout') ?>'" id="btnHeaderSair" aria-label="Sair da conta">
				<span class="material-symbols-outlined align-middle" style="font-size:1em; margin-right:0.3em;">logout</span>
				Sair
			</button>
		</div>
	</header>

	<div class="bannerApoioArtesa" role="region" aria-label="Área de apoio e configurações da conta">
		<div class="bannerApoioContainer">

			<div class="bannerApoioTopo">
				<div class="bannerApoioIdentidade">
					<div class="bannerApoioIcone">
						<span class="material-symbols-outlined">settings</span>
					</div>
					<div class="bannerApoioTitulo">
						<h1>Área do Ajudante</h1>
						<p>Configurações da conta de <strong style="color: #FDFBF7;"><?= esc($usuario->nome) ?></strong></p>
					</div>
				</div>

				<div class="badgeContaArtesa" role="note" aria-label="Esta área pertence à conta da artesã">
					<span class="material-symbols-outlined">person</span>
					Conta de <?= esc($usuario->nome) ?>
				</div>
			</div>

			<div class="bannerApoioAviso" role="note">
				<span class="material-symbols-outlined">info</span>
				<p>
					Esta área é para o <strong style="color: #FDFBF7;">ajudante técnico</strong> da artesã (filho, neto ou familiar).
					Aqui ficam as configurações da conta dela.
				</p>
			</div>

		</div>
	</div>

	<div class="areaVoltarArtesa" role="navigation" aria-label="Retorno ao painel principal">
		<a href="<?= base_url('artesa/dashboard') ?>"
			class="btnVoltarPainelArtesa"
			id="btnVoltarPaincipal"
			aria-label="Voltar para o painel principal da artesã <?= esc($usuario->nome) ?>">
			<span class="material-symbols-outlined">arrow_back</span>
			Voltar ao Painel Principal da Artesã
		</a>
	</div>

	<main class="areaConfigArtesa">

		<h2 class="secaoApoioTitulo">Relatórios</h2>

		<div class="cardConfig">
			<div class="cardConfigItem">
				<div class="cardConfigIcone" style="background-color: #fff3cd; color: #856404;">
					<span class="material-symbols-outlined">bar_chart</span>
				</div>
				<div class="cardConfigTexto">
					<strong>Relatório de Vendas</strong>
					<span>Veja quanto a artesã vendeu neste mês e nos meses anteriores.</span>
				</div>
				<div class="cardConfigAcao">
					<a href="<?= base_url('artesa/relatorio') ?>" class="linkConfigItem" aria-label="Abrir relatório de vendas">Ver →</a>
				</div>
			</div>

			<div class="cardConfigItem">
				<div class="cardConfigIcone" style="background-color: #d4edda; color: #155724;">
					<span class="material-symbols-outlined">payments</span>
				</div>
				<div class="cardConfigTexto">
					<strong>Saldo e Repasses</strong>
					<span>Acompanhe os valores a receber e pagamentos via Pix.</span>
				</div>
				<div class="cardConfigAcao">
					<span class="badgeEmBreve">Em Breve</span>
				</div>
			</div>
		</div>

		<h2 class="secaoApoioTitulo">Configurações Bancárias</h2>

		<div class="cardConfig">
			<div class="cardConfigItem">
				<div class="cardConfigIcone" style="background-color: #fdf6f6; color: #932121;">
					<svg width="24" height="24" viewBox="0 0 512 512" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M141.226 211.536L211.542 141.22a63.504 63.504 0 0 1 89.813 0L371.67 211.536l-32.84 32.84-70.315-70.315a17.067 17.067 0 0 0-24.135 0l-70.314 70.315-32.84-32.84zm159.227 88.928l-70.313 70.314a17.067 17.067 0 0 1-24.136 0l-70.314-70.314-32.84 32.84L173.165 403.62a63.505 63.505 0 0 0 89.813 0l70.315-70.315-32.84-32.84z"/><path d="M409.835 255.45l-38.165-38.165-32.84 32.84 26.097 26.098a17.067 17.067 0 0 1 0 24.135l-26.097 26.098 32.84 32.84 38.165-38.166a63.505 63.505 0 0 0 0-89.812zM102.165 255.45l38.165-38.165 32.84 32.84-26.097 26.098a17.067 17.067 0 0 0 0 24.135l26.097 26.098-32.84 32.84-38.165-38.166a63.505 63.505 0 0 1 0-89.812z"/></svg>
				</div>
				<div class="cardConfigTexto">
					<strong>Chave Pix</strong>
					<span><?= esc($usuario->email) ?></span>
				</div>
				<div class="cardConfigAcao">
					<span class="badgeAtivo">Ativo</span>
					<span class="badgeEmBreve">Edição em Breve</span>
				</div>
			</div>

			<div class="cardConfigItem">
				<div class="cardConfigIcone">
					<span class="material-symbols-outlined">account_balance</span>
				</div>
				<div class="cardConfigTexto">
					<strong>Conta Bancária</strong>
					<span>Nubank — Ag. 0001 · CC ••••-4521</span>
				</div>
				<div class="cardConfigAcao">
					<span class="badgeEmBreve">Em Breve</span>
				</div>
			</div>

			<div class="cardConfigItem">
				<div class="cardConfigIcone">
					<span class="material-symbols-outlined">calendar_month</span>
				</div>
				<div class="cardConfigTexto">
					<strong>Frequência de Repasse</strong>
					<span>Quinzenal — todo dia 5 e 20</span>
				</div>
				<div class="cardConfigAcao">
					<span class="badgeEmBreve">Em Breve</span>
				</div>
			</div>
		</div>

		<div class="notaPlaceholder" role="note" style="margin-top: 0.5em; background-color: #ffffff; padding: 1em; border-radius: 8px; font-family: 'Quicksand', sans-serif; font-size: 0.9em; display: flex; gap: 0.5em;">
			<span class="material-symbols-outlined" style="color:#b5a89e;">info</span>
			<span>
				Os campos de <strong>Configurações Bancárias</strong> estão previstos na arquitetura da plataforma
				e serão habilitados na próxima versão do MVP, após integração com gateway de pagamento.
			</span>
		</div>

		<h2 class="secaoApoioTitulo">Perfil da Artesã</h2>

		<div class="cardConfig">
			<div class="cardConfigItem">
				<div class="cardConfigIcone" style="background-color: #fdf6f6; color: #932121;">
					<span class="material-symbols-outlined">manage_accounts</span>
				</div>
				<div class="cardConfigTexto">
					<strong>Nome e Dados Pessoais</strong>
					<span><?= esc($usuario->nome) ?></span>
				</div>
				<div class="cardConfigAcao">
					<span class="badgeEmBreve">Edição em Breve</span>
				</div>
			</div>

			<div class="cardConfigItem">
				<div class="cardConfigIcone">
					<span class="material-symbols-outlined">email</span>
				</div>
				<div class="cardConfigTexto">
					<strong>E-mail de Acesso</strong>
					<span><?= esc($usuario->email) ?></span>
				</div>
				<div class="cardConfigAcao">
					<span class="badgeEmBreve">Em Breve</span>
				</div>
			</div>

			<div class="cardConfigItem">
				<div class="cardConfigIcone">
					<span class="material-symbols-outlined">lock</span>
				</div>
				<div class="cardConfigTexto">
					<strong>Alterar Senha</strong>
					<span>Última alteração: há 3 meses</span>
				</div>
				<div class="cardConfigAcao">
					<span class="badgeEmBreve">Em Breve</span>
				</div>
			</div>
		</div>

		<h2 class="secaoApoioTitulo">Precisa de Ajuda?</h2>

		<div class="cardConfig">
			<div class="cardConfigItem">
				<div class="cardConfigIcone" style="background-color: #e8f9f0; color: #25D366;">
					<span class="material-symbols-outlined">support_agent</span>
				</div>
				<div class="cardConfigTexto">
					<strong>Falar com o Suporte do Crochettei</strong>
					<span>Alguma dúvida técnica? A gente ajuda pelo WhatsApp.</span>
				</div>
				<div class="cardConfigAcao">
					<a href="https://wa.me/5511999999999?text=Olá!%20Sou%20ajudante%20da%20artesã%20<?= urlencode($usuario->nome) ?>%20e%20preciso%20de%20suporte%20técnico%20no%20Crochettei."
						target="_blank"
						class="linkConfigItem"
						aria-label="Abrir WhatsApp do suporte Crochettei">
						Falar no WhatsApp →
					</a>
				</div>
			</div>
		</div>

		<div class="mt-4">
			<a href="<?= base_url('artesa/dashboard') ?>"
				class="btnVoltarPainelArtesa"
				aria-label="Voltar para o painel principal da artesã">
				<span class="material-symbols-outlined">arrow_back</span>
				Voltar ao Painel Principal da Artesã
			</a>
		</div>

	</main>

	<footer class="footerBottom p-4 mt-auto bg-offwhite border-top border-fendi" style="background-color: #f8f6f0; border-top: 1px solid #e2dcd5; text-align: center;">
		<p style="font-family: 'Quicksand', sans-serif; color: #7e6f67;">Feito com <span class="material-symbols-outlined footerHeart" style="color: #932121; font-size: 1.1em; vertical-align: middle;">favorite</span> por Crochettei © 2026</p>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmxc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
		crossorigin="anonymous"></script>
</body>

</html>
