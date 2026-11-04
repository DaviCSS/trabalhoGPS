<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Edite ou exclua um produto da vitrine do Crochettei">
	<title>Crochettei — Editar Produto</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

<link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/cadastroProduto.css') ?>">
	<link rel="stylesheet" href="<?= base_url('css/editarProduto.css') ?>">
</head>

<body class="paginaCadastroProduto">

<header class="navBar">
		<div class="logo">
			<h1>Crochettei</h1>
		</div>
		<nav class="botoes">
			<button onclick="window.location.href='<?= base_url('artesa/produtos') ?>'">Voltar aos Meus Produtos</button>
		</nav>
	</header>

<?php if (session()->getFlashdata('sucesso') || session()->getFlashdata('erro')): ?>
<div class="toast-container position-fixed top-0 end-0 p-4" style="z-index: 1100;">
    <?php foreach (['sucesso' => 'text-bg-success', 'erro' => 'text-bg-danger'] as $tipo => $cls): ?>
        <?php if (session()->getFlashdata($tipo)): ?>
        <div class="toast align-items-center <?= $cls ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body fs-6 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined"><?= $tipo === 'sucesso' ? 'check_circle' : 'error' ?></span>
                    <?= esc(session()->getFlashdata($tipo)) ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>

	<div class="cabecalhoProduto">
		<h2>Editar Peça</h2>
		<p>Atualize as informações do seu crochê ou remova do catálogo.</p>
	</div>

	<main class="container">

		<form action="<?= base_url('artesa/produtos/editar/' . $produto->id) ?>" method="post" class="areaFormProduto" enctype="multipart/form-data" id="formEditarProduto">
		<?= csrf_field() ?>

<div class="cardFormularioProduto">
				<label class="labelGigante">
					Foto da Peça
				</label>
				<?php if ($produto->imagem_path): ?>
				<div class="areaUpload uploadPreenchido">
					<img src="<?= base_url($produto->imagem_path) ?>" alt="<?= esc($produto->nome) ?>" class="img-preview">
					<div class="overlayTrocarFoto">
						<span class="material-symbols-outlined fs-2">cameraswitch</span>
						<span>Trocar Foto</span>
					</div>
				</div>
				<?php endif; ?>
				<input type="file" id="fotoProduto" name="foto" accept="image/*" class="form-control mt-2">
				<div class="form-text text-fendi">Deixe em branco para manter a foto atual.</div>
			</div>

<div class="cardFormularioProduto">
				<label class="labelGigante" for="nomeProduto">Nome do Produto <span class="badgeObrigatorioProduto">Obrigatório</span></label>
				<input type="text" id="nomeProduto" name="nome" class="inputGigante mb-4" value="<?= esc($produto->nome) ?>" required>

				<label class="labelGigante" for="categoriaProduto">Categoria <span class="badgeObrigatorioProduto">Obrigatório</span></label>
				<select id="categoriaProduto" name="categoria_id" class="inputGigante mb-4" required>
					<?php foreach ($categorias as $id => $nome): ?>
					<option value="<?= (int)$id ?>" <?= $produto->categoria_id == $id ? 'selected' : '' ?>><?= esc($nome) ?></option>
					<?php endforeach; ?>
				</select>

				<div class="row g-3">
					<div class="col-6">
						<label class="labelGigante" for="precoProduto">Preço (R$) <span class="badgeObrigatorioProduto">Obrigatório</span></label>
						<input type="number" id="precoProduto" name="preco" class="inputGigante" value="<?= $produto->preco ?>" step="0.01" min="0" required>
					</div>
					<div class="col-6">
						<label class="labelGigante" for="tipoProduto">Tipo <span class="badgeObrigatorioProduto">Obrigatório</span></label>
						<select id="tipoProduto" name="tipo" class="inputGigante" onchange="toggleCampos()">
							<option value="pronta_entrega" <?= $produto->tipo === 'pronta_entrega' ? 'selected' : '' ?>>Pronta Entrega</option>
							<option value="sob_encomenda" <?= $produto->tipo === 'sob_encomenda' ? 'selected' : '' ?>>Sob Encomenda</option>
							<option value="misto" <?= $produto->tipo === 'misto' ? 'selected' : '' ?>>Pronta Entrega, mas aceito encomendas</option>
						</select>
					</div>
				</div>

				<div class="row g-3 mt-2">
					<div class="col-6" id="containerEstoque" style="display:none;">
						<label class="labelGigante" for="estoqueProduto">Estoque <span class="badgeObrigatorioProduto">Obrigatório</span></label>
						<input type="number" id="estoqueProduto" name="estoque" class="inputGigante" value="<?= (int)$produto->estoque ?>" min="0">
					</div>
					<div class="col-6" id="containerPrazo" style="display:none;">
						<label class="labelGigante" for="prazoProduto">Prazo (dias) <span class="badgeObrigatorioProduto">Obrigatório</span></label>
						<input type="number" id="prazoProduto" name="prazo_confeccao" class="inputGigante" value="<?= (int)$produto->prazo_confeccao ?>" min="1">
					</div>
				</div>
			</div>

<div class="cardFormularioProduto border-0 pb-0">
				<label class="labelGigante" for="descricaoProduto">Detalhes da Peça (Opcional)</label>
				<textarea id="descricaoProduto" name="descricao" class="inputGigante" rows="4"><?= esc($produto->descricao ?? '') ?></textarea>
			</div>

<div class="mt-5 text-center d-flex flex-column gap-3 justify-content-center align-items-center">
				<button type="submit" class="btn btnSalvarGigante" id="btnSalvar">
					<span class="material-symbols-outlined align-middle fs-4 me-2">save</span>
					Salvar Alterações
				</button>
			</div>

		</form>

		<!-- Botão Excluir que abre modal de confirmação -->
		<div class="text-center mt-3 mb-5">
			<button type="button" class="btn btnExcluirGigante" data-bs-toggle="modal" data-bs-target="#modalExcluir">
				<span class="material-symbols-outlined align-middle fs-4 me-2">delete_forever</span>
				Excluir Produto
			</button>
		</div>

		<!-- Formulário de exclusão: submetido pelo modal (não visível) -->
		<form id="formExcluir" action="<?= base_url('artesa/produtos/excluir/' . $produto->id) ?>" method="post">
		<?= csrf_field() ?>
		</form>

	</main>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="modalExcluir" tabindex="-1" aria-labelledby="modalExcluirLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modalFeedback bordaVermelha">
            <div class="modal-body text-center p-5">
                <div class="iconeFeedback perigo">
                    <span class="material-symbols-outlined">warning</span>
                </div>
                <h3 class="mt-4 mb-3 fw-bold text-cafe fs-jost" id="modalExcluirLabel">Tem certeza?</h3>
                <p class="fs-5 text-fendi mb-4">
                    Você quer mesmo apagar
                    <strong>"<?= esc($produto->nome) ?>"</strong>?
                    Essa ação não pode ser desfeita.
                </p>
                <div class="d-flex flex-column gap-3">
                    <button type="button" class="btn btn-ok-vermelho"
                        onclick="document.getElementById('formExcluir').submit()">
                        Sim, quero apagar
                    </button>
                    <button type="button" class="btn btn-cancelar" data-bs-dismiss="modal">Não, me enganei</button>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="footerBottom p-4 mt-auto bg-offwhite border-top border-fendi">
		<p>Feito com <span class="material-symbols-outlined footerHeart text-vinho">favorite</span> por Crochettei © 2026</p>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmxc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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

		// Inicializa Toasts de flashdata
		document.querySelectorAll('.toast').forEach(t => new bootstrap.Toast(t, { delay: 5000 }).show());

		// Preview de troca de foto
		document.getElementById('fotoProduto')?.addEventListener('change', function () {
			const file = this.files?.[0];
			if (!file) return;
			const reader = new FileReader();
			reader.onload = (e) => {
				const area = document.querySelector('.areaUpload');
				const img  = area?.querySelector('img.img-preview');
				if (img) img.src = e.target?.result;
			};
			reader.readAsDataURL(file);
		});
	</script>
</body>

</html>
