<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Cadastre uma nova peça de crochê na sua vitrine do Crochettei, passo a passo.">
	<title>Crochettei — Vender Novo Produto</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

<link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/wizard.css') ?>">
</head>

<body class="paginaCadastroProduto">

<header class="navBar">
		<div class="logo">
			<h1>Crochettei</h1>
		</div>
		<nav class="botoes">
			<button onclick="window.location.href='<?= base_url('artesa/dashboard') ?>'" aria-label="Cancelar e voltar ao meu painel">
				<span class="material-symbols-outlined align-middle" style="font-size:1em;">arrow_back</span>
				Cancelar e Voltar
			</button>
		</nav>
	</header>

<?php if (session()->getFlashdata('erro')): ?>
<div class="toast-container position-fixed top-0 end-0 p-4" style="z-index: 1100;">
	<div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
		<div class="d-flex">
			<div class="toast-body fs-6 d-flex align-items-center gap-2">
				<span class="material-symbols-outlined">error</span>
				<?= esc(session()->getFlashdata('erro')) ?>
			</div>
			<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
		</div>
	</div>
</div>
<?php endif; ?>

<div class="cabecalhoProduto">
		<h2>
			<span class="material-symbols-outlined align-middle me-1" style="font-size: 0.9em;">storefront</span>
			Vender um Novo Produto
		</h2>
		<p>Siga os passos abaixo. É rápido e fácil!</p>
	</div>

<nav class="wizardProgresso" aria-label="Progresso do cadastro de produto">
		<div class="wizardEtapa ativa" id="etapa-1" aria-current="step">
			<div class="wizardBolinha">1</div>
			<span class="wizardRotulo">Foto</span>
		</div>
		<div class="wizardEtapa" id="etapa-2">
			<div class="wizardBolinha">2</div>
			<span class="wizardRotulo">Nome e Preço</span>
		</div>
		<div class="wizardEtapa" id="etapa-3">
			<div class="wizardBolinha">3</div>
			<span class="wizardRotulo">Prazo</span>
		</div>
		<div class="wizardEtapa" id="etapa-4">
			<div class="wizardBolinha">4</div>
			<span class="wizardRotulo">Descrição</span>
		</div>
	</nav>
	<p class="wizardContador" id="wizardContador" aria-live="polite">Passo 1 de 4</p>

<main class="areaWizard">
		<form id="formProduto" action="<?= base_url('artesa/produtos/criar') ?>" method="post" enctype="multipart/form-data" novalidate>
		<?= csrf_field() ?>

<!-- ===================== PASSO 1: FOTO ===================== -->
<section class="wizardPasso ativo" id="passo-1" aria-label="Passo 1: Foto da peça">
			<div class="wizardCard">
				<h2 class="wizardTituloPasso">
					<span class="material-symbols-outlined">add_a_photo</span>
					Tire uma foto da sua peça
				</h2>
				<p class="wizardDescPasso">
					Uma boa foto faz toda a diferença! Tire em um lugar iluminado.<br>
					<strong>Pode usar a câmera do seu celular</strong> — é só tocar na área abaixo.
				</p>

				<div class="areaUpload" id="areaUploadFoto" role="button" tabindex="0" aria-label="Toque para escolher a foto da sua peça">
					<input type="file" id="fotoProduto" name="foto" accept="image/jpeg,image/png" style="display:none;" required>
					<span class="material-symbols-outlined" style="font-size: 3em; color: #b5a89e;">add_a_photo</span>
					<p>Toque aqui para adicionar a foto <span class="badgeObrigatorioProduto">Obrigatório</span></p>
					<small class="text-fendi">JPG ou PNG, máximo 2MB.</small>
				</div>
				<div id="erroFoto" class="text-danger mt-2 fw-bold text-center" style="display: none;">Por favor, selecione uma foto da peça.</div>
				<div id="previewFoto" class="mt-3" style="display:none;">
					<img id="previewImg" src="" alt="Preview da foto" style="max-height:220px; border-radius:16px; box-shadow: 0 4px 16px rgba(0,0,0,0.10);">
				</div>

				<div class="wizardNavegacao">
					<span></span>
					<button type="button" class="btnWizardProximo" onclick="irParaPasso(2)">
						Próximo
						<span class="material-symbols-outlined align-middle">arrow_forward</span>
					</button>
				</div>
			</div>
		</section>

<!-- ===================== PASSO 2: NOME, CATEGORIA e PREÇO ===================== -->
<section class="wizardPasso" id="passo-2" aria-label="Passo 2: Nome e preço">
			<div class="wizardCard">
				<h2 class="wizardTituloPasso">
					<span class="material-symbols-outlined">edit_note</span>
					O que você fez e por quanto vende?
				</h2>

				<label class="labelGigante" for="nomeProduto">
					Nome da Peça
					<span class="badgeObrigatorioProduto">Obrigatório</span>
				</label>
				<input type="text" id="nomeProduto" name="nome" class="inputGigante mb-4"
					placeholder="Ex.: Bolsa Macramê Off-White" required value="<?= old('nome') ?>">

				<label class="labelGigante" for="categoriaProduto">
					Categoria
					<span class="badgeObrigatorioProduto">Obrigatório</span>
				</label>
				<select id="categoriaProduto" name="categoria_id" class="inputGigante mb-4" required>
					<option value="">— Selecione uma categoria —</option>
					<?php foreach ($categorias as $id => $nome): ?>
					<option value="<?= (int)$id ?>" <?= old('categoria_id') == $id ? 'selected' : '' ?>><?= esc($nome) ?></option>
					<?php endforeach; ?>
				</select>

				<label class="labelGigante" for="precoProduto">
					Qual é o valor? (R$)
					<span class="badgeObrigatorioProduto">Obrigatório</span>
				</label>
				<input type="number" id="precoProduto" name="preco" class="inputGigante mb-4"
					placeholder="Ex.: 120,00" step="0.01" min="0.01" required inputmode="decimal" value="<?= old('preco') ?>">

				<label class="labelGigante" for="tipoProduto">
					Tipo de Produto
					<span class="badgeObrigatorioProduto">Obrigatório</span>
				</label>
				<select id="tipoProduto" name="tipo" class="inputGigante mb-4" required onchange="toggleCampos()">
					<option value="">— Selecione —</option>
					<option value="pronta_entrega" <?= old('tipo') == 'pronta_entrega' ? 'selected' : '' ?>>Tenho pronto (Pronta Entrega)</option>
					<option value="sob_encomenda" <?= old('tipo') == 'sob_encomenda' ? 'selected' : '' ?>>Faço sob encomenda</option>
					<option value="misto" <?= old('tipo') == 'misto' ? 'selected' : '' ?>>Pronta Entrega, mas aceito encomendas</option>
				</select>

				<div id="containerEstoque" style="display:none;">
					<label class="labelGigante" for="estoqueProduto">
						Estoque disponível
						<span class="badgeObrigatorioProduto">Obrigatório</span>
					</label>
					<input type="number" id="estoqueProduto" name="estoque" class="inputGigante mb-2"
						placeholder="Ex.: 5" min="0" required value="<?= old('estoque') ?>">
				</div>

				<div class="wizardNavegacao">
					<button type="button" class="btnWizardVoltar" onclick="irParaPasso(1)">
						<span class="material-symbols-outlined align-middle">arrow_back</span>
						Voltar
					</button>
					<button type="button" class="btnWizardProximo" onclick="irParaPasso(3)">
						Próximo
						<span class="material-symbols-outlined align-middle">arrow_forward</span>
					</button>
				</div>
			</div>
		</section>

<!-- ===================== PASSO 3: TIPO E PRAZO ===================== -->
<section class="wizardPasso" id="passo-3" aria-label="Passo 3: Prazo de confecção">
			<div class="wizardCard">
				<h2 class="wizardTituloPasso">
					<span class="material-symbols-outlined">schedule</span>
					Qual o prazo de entrega?
				</h2>
				<p class="wizardDescPasso">Quanto tempo você precisa para preparar esta peça?</p>

				<div id="containerPrazo">

				<label class="labelGigante" for="prazoProduto">
					Dias de Confecção
					<span class="badgeObrigatorioProduto">Obrigatório</span>
				</label>
				<input type="number" id="prazoProduto" name="prazo_confeccao" class="inputGigante"
					placeholder="Ex.: 7 dias" min="1" value="<?= old('prazo_confeccao') ?>">
				</div>

				<div class="wizardNavegacao">
					<button type="button" class="btnWizardVoltar" onclick="irParaPasso(2)">
						<span class="material-symbols-outlined align-middle">arrow_back</span>
						Voltar
					</button>
					<button type="button" class="btnWizardProximo" onclick="irParaPasso(4)">
						Próximo
						<span class="material-symbols-outlined align-middle">arrow_forward</span>
					</button>
				</div>
			</div>
		</section>

<!-- ===================== PASSO 4: DESCRIÇÃO ===================== -->
<section class="wizardPasso" id="passo-4" aria-label="Passo 4: Descrição da peça">
			<div class="wizardCard">
				<h2 class="wizardTituloPasso">
					<span class="material-symbols-outlined">notes</span>
					Conte mais sobre sua peça
				</h2>
				<p class="wizardDescPasso">
					Fale sobre os materiais usados, cores disponíveis, tamanho e cuidados.<br>
					<strong>Você pode usar a voz!</strong> Basta tocar no botão abaixo.
				</p>

				<label class="labelGigante" for="descricaoProduto">Detalhes da Peça (Opcional)</label>
				<textarea id="descricaoProduto" name="descricao" class="inputGigante" rows="5"
					placeholder="Ex.: Feita com fio 100% algodão, disponível nas cores branco e off-white..."><?= old('descricao') ?></textarea>

				<button type="button" id="btnVoz" class="btnVoz mt-3" onclick="ativarVoz()">
					<span class="material-symbols-outlined">mic</span> Tocar para Falar a Descrição
				</button>
				<p id="statusVoz" class="statusVoz" aria-live="polite"></p>

				<div class="wizardNavegacao">
					<button type="button" class="btnWizardVoltar" onclick="irParaPasso(3)">
						<span class="material-symbols-outlined align-middle">arrow_back</span>
						Voltar
					</button>
					<button type="submit" class="btnPublicar" id="btnPublicar">
						Publicar no Catálogo
					</button>
				</div>
			</div>
		</section>

		</form>
	</main>

<footer class="footerBottom p-4 mt-auto bg-offwhite border-top border-fendi">
		<p>Feito com <span class="material-symbols-outlined footerHeart text-vinho">favorite</span> por Crochettei © 2026</p>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmxc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
		crossorigin="anonymous"></script>

	<script>
		function toggleCampos() {
			const tipo = document.getElementById('tipoProduto').value;
			const prazoContainer = document.getElementById('containerPrazo');
			const prazoInput = document.getElementById('prazoProduto');
			const estoqueContainer = document.getElementById('containerEstoque');
			const estoqueInput = document.getElementById('estoqueProduto');

			if (tipo === 'pronta_entrega' || tipo === '') {
				if(prazoContainer) prazoContainer.style.display = 'none';
				if(prazoInput) prazoInput.required = false;
			} else {
				if(prazoContainer) prazoContainer.style.display = 'block';
				if(prazoInput) prazoInput.required = true;
			}

			if (tipo === 'sob_encomenda' || tipo === '') {
				if(estoqueContainer) estoqueContainer.style.display = 'none';
				if(estoqueInput) {
					estoqueInput.required = false;
					estoqueInput.value = '0';
				}
			} else {
				if(estoqueContainer) estoqueContainer.style.display = 'block';
				if(estoqueInput) estoqueInput.required = true;
			}
		}
		document.addEventListener('DOMContentLoaded', toggleCampos);

		// ============================================================
		// WIZARD DE NAVEGAÇÃO — Controla os 4 passos do formulário
		// ============================================================
		let passoAtual = 1;
		const totalPassos = 4;

		function irParaPasso(novoPasso) {
			const tipo = document.getElementById('tipoProduto')?.value;
			if (tipo === 'pronta_entrega') {
				if (passoAtual === 2 && novoPasso === 3) novoPasso = 4;
				else if (passoAtual === 4 && novoPasso === 3) novoPasso = 2;
			}

			if (novoPasso > passoAtual) {
				const passoAtualEl = document.getElementById(`passo-${passoAtual}`);
				if (passoAtualEl) {
					const campos = passoAtualEl.querySelectorAll('input, select, textarea');
					let valido = true;
					for (let i = 0; i < campos.length; i++) {
						if (!campos[i].checkValidity()) {
							valido = false;
							campos[i].value = ''; // apaga apenas o que está incorreto
							if (campos[i].type === 'file') {
								const erro = document.getElementById('erroFoto');
								if (erro) erro.style.display = 'block';
							} else {
								campos[i].reportValidity();
							}
							break;
						}
					}
					if (!valido) return;
				}
			}

			document.getElementById(`passo-${passoAtual}`)?.classList.remove('ativo');
			document.getElementById(`etapa-${passoAtual}`)?.classList.remove('ativa');

			if (novoPasso > passoAtual) {
				for (let p = passoAtual; p < novoPasso; p++) {
					document.getElementById(`etapa-${p}`)?.classList.add('concluida');
					const bolinha = document.querySelector(`#etapa-${p} .wizardBolinha`);
					if (bolinha) bolinha.innerHTML = '<span class="material-symbols-outlined" style="font-size:0.85em;">check</span>';
				}
			} else {
				for (let p = novoPasso; p <= passoAtual; p++) {
					document.getElementById(`etapa-${p}`)?.classList.remove('concluida');
					const bolinha = document.querySelector(`#etapa-${p} .wizardBolinha`);
					if (bolinha) bolinha.textContent = p;
				}
			}

			passoAtual = novoPasso;
			document.getElementById(`passo-${passoAtual}`)?.classList.add('ativo');
			const etapaEl = document.getElementById(`etapa-${passoAtual}`);
			etapaEl?.classList.add('ativa');
			etapaEl?.setAttribute('aria-current', 'step');

			document.getElementById('wizardContador').textContent = `Passo ${passoAtual} de ${totalPassos}`;
			window.scrollTo({ top: 0, behavior: 'smooth' });
		}

		// ============================================================
		// PREVIEW DE FOTO — Mostra miniatura antes do envio
		// ============================================================
		const areaUpload = document.getElementById('areaUploadFoto');
		const inputFoto  = document.getElementById('fotoProduto');

		areaUpload?.addEventListener('click', () => inputFoto?.click());
		areaUpload?.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ' ') inputFoto?.click(); });

		inputFoto?.addEventListener('change', function () {
			const file = this.files?.[0];
			if (!file) return;
			
			const erro = document.getElementById('erroFoto');
			if (erro) erro.style.display = 'none';

			const reader = new FileReader();
			reader.onload = (e) => {
				const preview = document.getElementById('previewFoto');
				const img     = document.getElementById('previewImg');
				if (preview && img) {
					img.src = e.target?.result;
					preview.style.display = 'block';
					areaUpload.style.borderColor = '#4a6741';
				}
			};
			reader.readAsDataURL(file);
		});

		// ============================================================
		// RECONHECIMENTO DE VOZ — Passo 4 (descrição por voz)
		// ============================================================
		let recognition = null;
		let escutando   = false;

		function ativarVoz() {
			const btnVoz    = document.getElementById('btnVoz');
			const statusVoz = document.getElementById('statusVoz');
			const textarea  = document.getElementById('descricaoProduto');

			const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
			if (!SpeechRecognition) {
				statusVoz.textContent = 'Seu aparelho não suporta reconhecimento de voz. Por favor, escreva normalmente.';
				return;
			}

			if (escutando) { recognition?.stop(); return; }

			recognition = new SpeechRecognition();
			recognition.lang = 'pt-BR';
			recognition.continuous = false;
			recognition.interimResults = true;
			recognition.maxAlternatives = 1;

			recognition.onstart = () => {
				escutando = true;
				btnVoz.classList.add('escutando');
				btnVoz.innerHTML = '<span class="material-symbols-outlined">stop_circle</span> Escutando... (toque para parar)';
				statusVoz.textContent = 'Pode falar agora! Estou escutando...';
				statusVoz.classList.add('ativo');
			};

			recognition.onresult = (event) => {
				let textoFinal = '', textoInter = '';
				for (let i = event.resultIndex; i < event.results.length; i++) {
					const t = event.results[i][0].transcript;
					if (event.results[i].isFinal) textoFinal += t;
					else textoInter += t;
				}
				if (textarea) textarea.value = (textarea.value + ' ' + textoFinal).trim();
				statusVoz.textContent = textoInter ? `Ouvindo: "${textoInter}"...` : '';
			};

			recognition.onerror = () => {
				statusVoz.textContent = 'Não consegui entender. Tente falar mais devagar ou escreva diretamente.';
				statusVoz.classList.remove('ativo');
				resetarVoz();
			};

			recognition.onend = () => {
				escutando = false;
				resetarVoz();
				statusVoz.textContent = 'Pronto! O que você falou já foi escrito no campo acima.';
				statusVoz.classList.remove('ativo');
			};

			recognition.start();
		}

		function resetarVoz() {
			const btnVoz = document.getElementById('btnVoz');
			if (btnVoz) {
				btnVoz.classList.remove('escutando');
				btnVoz.innerHTML = '<span class="material-symbols-outlined">mic</span> Tocar para Falar a Descrição';
			}
			escutando = false;
		}

		// ============================================================
		// SUBMIT — Feedback visual ao publicar
		// ============================================================
		document.getElementById('formProduto')?.addEventListener('submit', function () {
			const btn = document.getElementById('btnPublicar');
			if (btn) {
				btn.textContent = 'Publicando...';
				btn.disabled = true;
			}
		});

		// Inicializa Toasts de flashdata
		document.querySelectorAll('.toast').forEach(t => new bootstrap.Toast(t, { delay: 5000 }).show());
	</script>
</body>

</html>
