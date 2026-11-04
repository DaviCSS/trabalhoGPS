<?php
// View: cliente/rastreio.php — UC11 Rastrear Encomenda
// Layout segue o padrão visual existente do projeto
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crochettei — Rastreio do Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300..700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">
    <link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
</head>
<body class="bg-offwhite">

<header class="navBar">
    <div class="logo"><h1>Crochettei</h1></div>
    <nav class="botoes">
        <button onclick="window.location.href='<?= base_url('cliente/pedidos') ?>'">Meus Pedidos</button>
    </nav>
    <div class="botLog">
        <button onclick="window.location.href='<?= base_url('logout') ?>'" id="btnHeaderSair">Sair</button>
    </div>
</header>

<main class="container py-5" style="max-width: 700px;">
    <div class="card shadow-sm border-0 rounded-4 p-4">
        <div class="text-center mb-4">
            <span class="material-symbols-outlined" style="font-size:3rem; color: var(--cor-vinho, #7a2d2d);">
                <?= esc($rastreio['icone']) ?>
            </span>
            <h2 class="fs-jost mt-2">Pedido #<?= (int) $rastreio['pedido_id'] ?></h2>
            <p class="text-muted"><?= esc($rastreio['descricao']) ?></p>
            <?php if ($rastreio['codigo_rastreio']): ?>
                <span class="badge bg-secondary px-3 py-2 fs-6">
                    Cód. Rastreio: <?= esc($rastreio['codigo_rastreio']) ?>
                </span>
            <?php else: ?>
                <span class="badge bg-light text-muted px-3 py-2">Código de rastreio ainda não gerado</span>
            <?php endif; ?>
        </div>

        <h5 class="fw-bold mb-3">📦 Histórico de Eventos</h5>
        <div class="timeline">
            <?php foreach (array_reverse($rastreio['eventos']) as $i => $evento): ?>
            <div class="d-flex mb-3 <?= $i === 0 ? 'fw-bold' : 'text-muted' ?>">
                <div class="me-3 text-center" style="min-width:40px;">
                    <span class="material-symbols-outlined" style="font-size:1.3rem;">
                        <?= $i === 0 ? 'radio_button_checked' : 'radio_button_unchecked' ?>
                    </span>
                </div>
                <div>
                    <span class="d-block"><?= esc($evento['evento']) ?></span>
                    <small class="text-secondary"><?= esc($evento['data']) ?></small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4">
            <a href="<?= base_url('cliente/pedidos') ?>" class="btnAcaoPrincipal text-decoration-none">
                ← Voltar aos Meus Pedidos
            </a>
        </div>
    </div>
</main>

<footer class="footerBottom p-4 text-center border-top border-fendi bg-white mt-5">
    <p>Feito com <span class="material-symbols-outlined footerHeart text-vinho">favorite</span> por Crochettei © 2026</p>
</footer>

</body>
</html>
