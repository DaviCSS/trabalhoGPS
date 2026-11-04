<?php

use CodeIgniter\Router\RouteCollection;

$routes->get('/', static function () {
    $produtos = (new \App\Models\ProdutoModel())->listarParaVitrine(null, 3);
    return view('home', ['destaques' => $produtos]);
});
$routes->get('/home', static function () {
    return redirect()->to('/');
});

$routes->get('/catalogo',        'CheckoutController::catalogo');
$routes->get('/produto/(:num)',  'CheckoutController::detalhe/$1');

// --- Autenticação ---
$routes->get( '/login',  'AuthController::exibirLogin');
$routes->post('/login',  'AuthController::processarLogin');

$routes->get( 'recuperar-senha',            'AuthController::recuperarSenha');
$routes->post('recuperar-senha',            'AuthController::processarRecuperarSenha');
$routes->get( 'redefinir-senha/(:segment)', 'AuthController::exibirNovaSenha/$1');
$routes->post('redefinir-senha/(:segment)', 'AuthController::processarNovaSenha/$1');

$routes->get('/logout', 'AuthController::logout');

$routes->get( '/cadastro/cliente', 'AuthController::exibirCadastroCliente');
$routes->post('/cadastro/cliente', 'AuthController::cadastrarCliente');

$routes->get( '/cadastro/artesa',  'AuthController::exibirCadastroArtesa');
$routes->post('/cadastro/artesa',  'AuthController::cadastrarArtesa');

$routes->get('/cadastro', static function () {
    return view('auth/escolha_conta');
});

// --- Área autenticada (todos os perfis) ---
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get( '/carrinho',                'CheckoutController::verCarrinho');
    $routes->post('/carrinho/adicionar',      'CheckoutController::adicionarAoCarrinho');
    $routes->post('/carrinho/remover/(:num)', 'CheckoutController::removerDoCarrinho/$1');
    $routes->post('/carrinho/atualizar',      'CheckoutController::atualizarCarrinho');

    // UC07 — Calcular frete
    $routes->post('/carrinho/frete',          'CheckoutController::calcularFrete');

    $routes->get( '/checkout',           'CheckoutController::exibirCheckout');
    $routes->post('/checkout/finalizar', 'CheckoutController::finalizarPedido');
});

// --- Área do cliente ---
$routes->group('/cliente', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard',              'CheckoutController::dashboardCliente');
    $routes->get('pedidos',                'CheckoutController::meusPedidos');
    // UC11 — Rastrear encomenda
    $routes->get('pedidos/rastrear/(:num)', 'CheckoutController::rastrearPedido/$1');
});

// --- Área da artesã ---
$routes->group('/artesa', ['filter' => 'artesa'], static function ($routes) {
    $routes->get('dashboard', 'ArtesaController::dashboard');
    $routes->get('apoio',     'ArtesaController::apoio');
    $routes->get('relatorio', 'ArtesaController::relatorio');
    $routes->get('relatorio/exportar', 'ArtesaController::exportarRelatorio');
    $routes->post('vendas/criar', 'ArtesaController::registrarVenda');
    $routes->post('pix/salvar', 'ArtesaController::salvarChavePix');

    $routes->get( 'produtos',               'ArtesaController::listarProdutos');
    $routes->get( 'produtos/criar',         'ArtesaController::exibirFormCriacao');
    $routes->post('produtos/criar',         'ArtesaController::criarProduto');
    $routes->get( 'produtos/editar/(:num)', 'ArtesaController::exibirFormEdicao/$1');
    $routes->post('produtos/editar/(:num)', 'ArtesaController::atualizarProduto/$1');
    $routes->post('produtos/excluir/(:num)','ArtesaController::excluirProduto/$1');

    $routes->get( 'pedidos',                    'ArtesaController::listarPedidos');
    $routes->post('pedidos/pagamento/(:num)', 'ArtesaController::confirmarPagamento/$1');
    $routes->post('pedidos/status/(:num)',       'ArtesaController::atualizarStatusPedido/$1');
});

// --- Área administrativa ---
$routes->group('/admin', ['filter' => 'admin'], static function ($routes) {
    $routes->get( 'dashboard',                       'AdminController::dashboard');
    $routes->post('pedidos/pagamento/(:num)',         'AdminController::aprovarPagamento/$1');
    $routes->post('pedidos/cancelar/(:num)',          'AdminController::forcarCancelamento/$1');
    // UC09 — Estorno
    $routes->post('pagamentos/estornar/(:num)',       'AdminController::estornar/$1');
});
