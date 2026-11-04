<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Crochettei — a plataforma que cuida da logística e dos pagamentos para você focar no que ama: criar.">
	<title>Crochettei — Sua arte, nossa plataforma</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

	<link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/home.css') ?>">
</head>

<body>

<header class="navBar">
		<div class="logo">
			<h1>Crochettei</h1>
		</div>
		<nav class="botoes">
			<button onclick="window.location.href='<?= base_url('catalogo') ?>'">Catálogo</button>
		</nav>
		<div class="botLog">
			<?php if (session()->get('logado')): ?>
				<?php if (session()->get('user_tipo') === 'artesa'): ?>
					<button onclick="window.location.href='<?= base_url('artesa/dashboard') ?>'" id="btnHeaderLogin">Meu Painel</button>
				<?php else: ?>
					<button onclick="window.location.href='<?= base_url('cliente/dashboard') ?>'" id="btnHeaderLogin">Meu Painel</button>
				<?php endif; ?>
			<?php else: ?>
				<button onclick="window.location.href='<?= base_url('login') ?>'" id="btnHeaderLogin">Login</button>
			<?php endif; ?>
		</div>
	</header>

	<main>

<section class="homeHero w-100">
			<img src="<?= base_url('img/Prancheta 2.jpeg') ?>" alt="Artesã fazendo crochê" class="heroImage">
			<div class="heroTexto">
				<span class="badgeDestaque">Feito para artesãs do Espírito Santo</span>
				<h1>
					Você cria.<br>
					<span>A gente cuida do resto.</span>
				</h1>
				<p>
					O Crochettei resolve a logística e os pagamentos para você.
					Seu único trabalho é fazer o crochê com amor.
				</p>
			</div>
		</section>

<section class="destaquesCarrossel w-100 py-5 bg-light">
			<div class="container">
				<div class="text-center mb-4">
					<h2 class="fs-jost text-cafe">Destaques da Semana</h2>
					<p class="text-fendi">Peças feitas com muito amor e carinho por nossas artesãs.</p>
				</div>

<?php if (!empty($destaques)): ?>
<div id="carouselDestaques" class="carousel slide" data-bs-ride="carousel">
					<div class="carousel-indicators">
					<?php $index = 0; foreach ($destaques as $p): ?>
						<button type="button" data-bs-target="#carouselDestaques" data-bs-slide-to="<?= $index ?>"
							<?= $index === 0 ? 'class="active" aria-current="true"' : '' ?>
							aria-label="Slide <?= $index + 1 ?>"></button>
					<?php $index++; endforeach; ?>
					</div>
					<div class="carousel-inner rounded shadow-sm border border-2 border-fendi">
					<?php $index = 0; foreach ($destaques as $p): ?>
						<div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
							<?php if ($p->imagem_path): ?>
							<?php $imgSrc = str_starts_with($p->imagem_path, 'http') ? $p->imagem_path : base_url($p->imagem_path); ?>
							<img src="<?= $imgSrc ?>" class="d-block w-100" style="height:400px; object-fit:cover; background-color: #f8f6f0;" alt="<?= esc($p->nome) ?>" onerror="this.onerror=null; this.src='https://placehold.co/600x400/e2dcd5/2b1f1a?text=Foto+Indisponível';">
							<?php else: ?>
							<div style="height:400px; background-color:#e2dcd5;" class="d-block w-100 d-flex align-items-center justify-content-center">
								<span class="material-symbols-outlined text-vinho" style="font-size: 4rem;">styler</span>
							</div>
							<?php endif; ?>
							<div class="carousel-caption d-none d-md-block bg-overlay-cafe">
								<h5><?= esc($p->nome) ?></h5>
								<p>R$ <?= number_format($p->preco, 2, ',', '.') ?></p>
							</div>
						</div>
					<?php $index++; endforeach; ?>
					</div>
					<button class="carousel-control-prev" type="button" data-bs-target="#carouselDestaques" data-bs-slide="prev">
						<span class="carousel-control-prev-icon filter-invert" aria-hidden="true"></span>
						<span class="visually-hidden">Anterior</span>
					</button>
					<button class="carousel-control-next" type="button" data-bs-target="#carouselDestaques" data-bs-slide="next">
						<span class="carousel-control-next-icon filter-invert" aria-hidden="true"></span>
						<span class="visually-hidden">Próximo</span>
					</button>
				</div>
<?php else: ?>
<div class="text-center py-5 text-fendi">
					<span class="material-symbols-outlined" style="font-size:3rem;">auto_awesome</span>
					<p class="mt-2">Em breve, novos destaques!</p>
				</div>
<?php endif; ?>

			</div>
		</section>

<section class="ctaDivisao w-100">
			<div class="container">

				<div class="tituloSecao">
					<h2>O que você deseja fazer?</h2>
					<p>Escolha o seu perfil e comece agora mesmo.</p>
				</div>

<div class="row g-4 justify-content-center">

<div class="col-12 col-md-6 col-lg-5">
						<div class="cardPerfil">
							<div class="iconePerfil artesa">
								<span class="material-symbols-outlined">palette</span>
							</div>
							<h3>Sou Artesã</h3>
							<p>Quero cadastrar meus produtos e vender meu crochê pela internet com segurança.</p>
							<ul>
								<li><span class="check">✓</span> Logística resolvida</li>
								<li><span class="check">✓</span> Pagamento garantido</li>
								<li><span class="check">✓</span> Cadastro gratuito</li>
								<li><span class="check">✓</span> Suporte fácil</li>
							</ul>
							<a href="<?= base_url('cadastro/artesa') ?>" class="btnArtesa" id="ctaArtesa">
								Quero vender meu crochê →
							</a>
						</div>
					</div>

<div class="col-12 col-md-6 col-lg-5">
						<div class="cardPerfil">
							<div class="iconePerfil cliente">
								<span class="material-symbols-outlined">shopping_bag</span>
							</div>
							<h3>Sou Cliente</h3>
							<p>Quero comprar peças de crochê artesanais, feitas com carinho por mulheres talentosas.</p>
							<ul>
								<li><span class="check">✓</span> Peças únicas e artesanais</li>
								<li><span class="check">✓</span> Compra segura</li>
								<li><span class="check">✓</span> Entrega em todo o Brasil</li>
								<li><span class="check">✓</span> Apoia quem produz</li>
							</ul>
							<a href="<?= base_url('cadastro/cliente') ?>" class="btnCliente" id="ctaCliente">
								Quero comprar artesanato →
							</a>
						</div>
					</div>

				</div>

<p class="text-center mt-4 text-fendi fs-6">
					Já tem uma conta?
					<a href="<?= base_url('login') ?>" class="text-vinho fw-bold text-decoration-none">
						Entrar agora →
					</a>
				</p>

			</div>
		</section>

<section class="pilares w-100">
			<div class="container">

				<div class="sectionHeader">
					<h2>Por que o Crochettei é diferente?</h2>
					<p>Uma plataforma pensada do zero para artesãs e compradores do Brasil.</p>
				</div>

				<div class="row g-4 justify-content-center">
					<div class="col-12 col-sm-6 col-lg-4">
						<div class="pilarCard h-100">
							<div class="iconePilar">
								<span class="material-symbols-outlined">local_shipping</span>
							</div>
							<h4>Logística Resolvida</h4>
							<p>Nós geramos a etiqueta de envio. Você só leva o pacote ao Correios. Sem burocracia.</p>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4">
						<div class="pilarCard h-100">
							<div class="iconePilar">
								<span class="material-symbols-outlined">payments</span>
							</div>
							<h4>Pagamento Seguro</h4>
							<p>O dinheiro cai direto na sua conta via Pix, após a confirmação do pedido. Sem riscos.</p>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-4">
						<div class="pilarCard h-100">
							<div class="iconePilar">
								<span class="material-symbols-outlined">auto_awesome</span>
							</div>
							<h4>Simples para Usar</h4>
							<p>Se você usa o celular para mandar mensagem, você consegue usar o Crochettei. Prometemos.</p>
						</div>
					</div>
				</div>

			</div>
		</section>

<section class="mensagemEmpoderamento w-100">
			<div class="container">
				<h2>Você não precisa entender de <strong>tecnologia.</strong></h2>
				<p>
					Nossa plataforma foi criada por pessoas que entendem as dificuldades de quem está
					começando a usar a internet. Cada botão, cada tela foi feita pensando em você.
				</p>
				<a href="<?= base_url('cadastro') ?>"
					class="landingFraseButtons-left text-decoration-none d-inline-block rounded-pill">
					Começar agora — é grátis
				</a>
			</div>
		</section>

	</main>

<footer>
		<div class="footerContent">
			<div class="footerCol">
				<h3>crochettei</h3>
				<p>Marketplace de crochê artesanal.<br>
				Conectando artesãs talentosas a quem valoriza o feito à mão.</p>
			</div>
			<div class="footerCol">
				<h4>Navegação</h4>
				<a href="<?= base_url('/') ?>">Início</a>
				<a href="<?= base_url('catalogo') ?>">Produtos</a>
			</div>
			<div class="footerCol">
				<h4>Conta</h4>
				<a href="<?= base_url('login') ?>">Entrar</a>
				<a href="<?= base_url('cadastro/artesa') ?>">Sou Artesã</a>
				<a href="<?= base_url('cadastro/cliente') ?>">Sou Cliente</a>
			</div>
			<div class="footerCol">
				<h4>Suporte</h4>
				<a href="javascript:void(0)">Ajuda</a>
				<a href="javascript:void(0)">Termos de Uso</a>
				<a href="javascript:void(0)">Privacidade</a>
			</div>
		</div>
		<div class="footerBottom">
			<p>Feito com <span class="material-symbols-outlined footerHeart">favorite</span> por Crochettei © 2026</p>
		</div>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			var myCarousel = document.querySelector('#carouselDestaques');
			if (myCarousel) {
				new bootstrap.Carousel(myCarousel, {
					interval: 4000,
					wrap: true
				});
			}
		});
	</script>
</body>

</html>
