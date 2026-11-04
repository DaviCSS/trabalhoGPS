<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Consulte o histórico de vendas e baixe seu resumo financeiro.">
    <title>Crochettei — Relatório de Vendas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined">

    <link rel="stylesheet" href="<?= base_url('css/stylesheet.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/relatorio.css') ?>">
</head>

<body class="paginaDashboard">

<header class="navBar">
    <div class="logo"><h1>Crochettei</h1></div>
    <nav class="botoes">
        <button onclick="window.location.href='<?= base_url('artesa/apoio') ?>'">
            <span class="material-symbols-outlined align-middle">arrow_back</span>
            Voltar
        </button>
    </nav>
    <div class="botLog">
        <button onclick="window.location.href='<?= base_url('logout') ?>'" id="btnHeaderSair">Sair</button>
    </div>
</header>

<div class="dashboardHeader">
    <h2><span class="material-symbols-outlined align-middle me-2">bar_chart</span>Minhas Vendas</h2>
    <p>Veja quanto recebeu e quais vendas aconteceram em cada período.</p>
</div>

<main class="container py-4 px-3 px-md-4">
    <form method="get" action="<?= base_url('artesa/relatorio') ?>" class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mb-4">
        <h3 class="fs-5 text-vinho fw-bold">Escolha o período</h3>
        <div class="d-flex gap-2 flex-wrap mb-3">
            <a class="btn <?= $periodo === 'semana' ? 'btn-dark' : 'btn-outline-secondary' ?>" href="<?= base_url('artesa/relatorio?periodo=semana') ?>">Esta semana</a>
            <a class="btn <?= $periodo === 'mes_atual' ? 'btn-dark' : 'btn-outline-secondary' ?>" href="<?= base_url('artesa/relatorio?periodo=mes_atual') ?>">Este mês</a>
            <a class="btn <?= $periodo === 'mes_anterior' ? 'btn-dark' : 'btn-outline-secondary' ?>" href="<?= base_url('artesa/relatorio?periodo=mes_anterior') ?>">Mês passado</a>
            <a class="btn <?= $periodo === 'total' ? 'btn-dark' : 'btn-outline-secondary' ?>" href="<?= base_url('artesa/relatorio?periodo=total') ?>">Tudo</a>
        </div>
        <label for="mesRelatorio" class="form-label fw-bold">Ou escolha um mês específico</label>
        <div class="row g-2">
            <div class="col-12 col-sm-5">
                <input type="month" class="form-control form-control-lg" id="mesRelatorio" name="mes"
                    value="<?= esc($mes) ?>" required>
                <input type="hidden" name="periodo" value="mes">
            </div>
            <div class="col-12 col-sm-auto">
                <button class="btn btn-lg text-white" type="submit" style="background:#8C2C36;">Buscar vendas</button>
            </div>
        </div>
    </form>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h3 class="fs-5 fw-bold text-vinho m-0">Resultado: <?= esc($resumo['periodo']) ?></h3>
        <?php $consultaExportacao = http_build_query(['periodo' => $periodo, 'mes' => $mes]); ?>
        <a class="btn btn-lg text-white" style="background:#8C2C36;"
            href="<?= base_url('artesa/relatorio/exportar') . '?' . esc($consultaExportacao, 'attr') ?>">
            <span class="material-symbols-outlined align-middle">download</span>
            Baixar relatório (.txt)
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="cardMetrica">
                <div class="metricaIcone" style="background:#fdf6f6;">
                    <span class="material-symbols-outlined text-vinho">payments</span>
                </div>
                <p class="metricaRotulo">Total Recebido</p>
                <div class="metricaValor">R$ <?= number_format($resumo['totalRecebido'], 2, ',', '.') ?></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="cardMetrica">
                <div class="metricaIcone" style="background:#eaf0ea;">
                    <span class="material-symbols-outlined text-oliva">inventory_2</span>
                </div>
                <p class="metricaRotulo">Vendas Recebidas</p>
                <div class="metricaValor"><?= (int) $resumo['totalVendas'] ?></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="cardMetrica">
                <div class="metricaIcone" style="background:#fff3cd;">
                    <span class="material-symbols-outlined" style="color:#856404;">hourglass_empty</span>
                </div>
                <p class="metricaRotulo">Aguardando Pagamento</p>
                <div class="metricaValor"><?= (int) $resumo['pendentes'] ?></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="cardMetrica">
                <div class="metricaIcone" style="background:#eaf0ea;">
                    <span class="material-symbols-outlined text-oliva">check_circle</span>
                </div>
                <p class="metricaRotulo">Pedidos Entregues</p>
                <div class="metricaValor"><?= (int) $resumo['entregues'] ?></div>
            </div>
        </div>
    </div>

    <div class="tabelaVendas">
        <div class="tabelaVendasCabecalho"><h3>Histórico de vendas</h3></div>
        <div class="table-responsive">
            <table class="table tabelaSimples" aria-label="Histórico de vendas no período">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Venda ou produto</th>
                        <th>Origem</th>
                        <th>Situação</th>
                        <th class="text-end">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($resumo['registros'])): ?>
                        <tr><td colspan="5" class="text-center py-4">Ainda não existem vendas nesse período.</td></tr>
                    <?php else: ?>
                        <?php foreach ($resumo['registros'] as $registro): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($registro['data'])) ?></td>
                                <td class="fw-bold"><?= esc($registro['produto']) ?></td>
                                <td><?= esc($registro['tipo']) ?></td>
                                <td>
                                    <span class="badgeStatus <?= $registro['recebido'] ? 'badge-verde' : 'badge-amarelo' ?>">
                                        <?= esc($registro['situacao']) ?>
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-cafe">R$ <?= number_format($registro['valor'], 2, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr class="tabelaRodape">
                        <td colspan="4"><strong>Total recebido no período</strong></td>
                        <td class="text-end"><strong>R$ <?= number_format($resumo['totalRecebido'], 2, ',', '.') ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <p class="text-fendi mt-3">Pedidos pendentes ou cancelados aparecem no histórico, mas não entram no valor recebido.</p>
</main>

<footer class="footerBottom p-4 mt-auto bg-offwhite border-top border-fendi">
    <p>Feito com <span class="material-symbols-outlined footerHeart text-vinho">favorite</span> por Crochettei © 2026</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
