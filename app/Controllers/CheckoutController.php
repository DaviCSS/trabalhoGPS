<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\ProdutoModel;
use App\Models\PedidoModel;
use App\Models\CategoriaModel;
use App\Models\ClienteModel;
use App\Models\EnderecoModel;
use App\Models\ItemPedidoModel;
use App\UseCases\GerenciarCarrinhoUseCase;
use App\UseCases\CheckoutUseCase;
use App\UseCases\CalcularFreteComercialUseCase;
use App\UseCases\RastrearEncomendaUseCase;
use CodeIgniter\Controller;

class CheckoutController extends Controller
{
    // Monta a vitrine principal listando todos os produtos ativos
    public function catalogo(): string
    {
        $categoriaId  = (int) ($this->request->getGet('categoria_id') ?? 0) ?: null;
        $produtoModel = new ProdutoModel();

        $produtos   = $produtoModel->listarParaVitrine($categoriaId);
        $categorias = (new CategoriaModel())->listarTodasOrdenadas();

        $carrinhoUseCase = new GerenciarCarrinhoUseCase(session());
        $qtdCarrinho     = $carrinhoUseCase->contarItens();

        return view('catalogo/vitrine', [
            'produtos'             => $produtos,
            'categorias'           => $categorias,
            'categoriaSelecionada' => $categoriaId,
            'qtdCarrinho'          => $qtdCarrinho,
        ]);
    }

    public function detalhe(int $id): string
    {
        $produto = (new ProdutoModel())->buscarComDetalhes($id);

        if ($produto === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Produto não encontrado.'
            );
        }

        $carrinhoUseCase = new GerenciarCarrinhoUseCase(session());
        $qtdCarrinho     = $carrinhoUseCase->contarItens();

        return view('catalogo/detalhe_produto', [
            'produto'     => $produto,
            'qtdCarrinho' => $qtdCarrinho,
        ]);
    }

    public function verCarrinho(): string
    {
        $carrinhoUseCase = new GerenciarCarrinhoUseCase(session());

        // UC07 — Calcula frete se houver CEP na sessão
        $freteInfo = null;
        $cepSessao = session()->get('cep_frete');
        if ($cepSessao) {
            $freteUseCase = new CalcularFreteComercialUseCase();
            $freteInfo    = $freteUseCase->calcular($cepSessao, $carrinhoUseCase->calcularTotal());
        }

        return view('checkout/carrinho', [
            'itens'     => $carrinhoUseCase->obterCarrinho(),
            'total'     => $carrinhoUseCase->calcularTotal(),
            'qtdItens'  => $carrinhoUseCase->contarItens(),
            'freteInfo' => $freteInfo,
        ]);
    }

    // Tenta colocar o produto no carrinho, mas antes checa se ainda tem estoque
    public function adicionarAoCarrinho()
    {
        $produtoId  = (int) $this->request->getPost('produto_id');
        $quantidade = max(1, (int) $this->request->getPost('quantidade'));

        $produto = (new ProdutoModel())->buscarComDetalhes($produtoId);

        if ($produto === null) {
            session()->setFlashdata('erro', 'Produto não encontrado.');
            return redirect()->to('/catalogo');
        }

        $useCase    = new GerenciarCarrinhoUseCase(session());
        $carrinho   = $useCase->obterCarrinho();
        $qtdAtual   = isset($carrinho[$produtoId]) ? $carrinho[$produtoId]['quantidade'] : 0;

        if ($produto->tipo === 'pronta_entrega' && ($qtdAtual + $quantidade) > $produto->estoque) {
            session()->setFlashdata('erro', "Estoque insuficiente. Temos apenas {$produto->estoque} unidade(s) de '{$produto->nome}'.");
            return redirect()->back();
        }

        $carrinhoAntigo = $useCase->obterCarrinho();
        $artesaoAntigo  = !empty($carrinhoAntigo) ? reset($carrinhoAntigo)['artesao_id'] : null;

        try {
            $useCase->adicionar([
                'produto_id'  => $produto->id,
                'nome'        => $produto->nome,
                'preco'       => $produto->preco,
                'artesao_id'  => $produto->artesao_id,
                'imagem_path' => $produto->imagem_path,
            ], $quantidade);

            if ($artesaoAntigo !== null && $artesaoAntigo !== $produto->artesao_id) {
                session()->setFlashdata('aviso', "Como você escolheu um produto de uma nova artesã, seu carrinho anterior foi esvaziado. '{$produto->nome}' foi adicionado!");
            } else {
                session()->setFlashdata('sucesso', "'{$produto->nome}' adicionado ao carrinho!");
            }

        } catch (\RuntimeException $e) {
            session()->setFlashdata('aviso', $e->getMessage());
        }

        return redirect()->to('/carrinho');
    }

    public function removerDoCarrinho(int $produtoId)
    {
        (new GerenciarCarrinhoUseCase(session()))->remover($produtoId);
        session()->setFlashdata('sucesso', 'Item removido do carrinho.');
        return redirect()->to('/carrinho');
    }

    public function atualizarCarrinho()
    {
        $produtoId  = (int) $this->request->getPost('produto_id');
        $quantidade = (int) $this->request->getPost('quantidade');

        $produto = (new ProdutoModel())->find($produtoId);

        if ($produto && $produto->tipo === 'pronta_entrega' && $quantidade > $produto->estoque) {
            session()->setFlashdata('erro', "Estoque insuficiente. Temos apenas {$produto->estoque} unidade(s) de '{$produto->nome}'.");
            return redirect()->to('/carrinho');
        }

        (new GerenciarCarrinhoUseCase(session()))->atualizarQuantidade($produtoId, $quantidade);
        return redirect()->to('/carrinho');
    }

    /**
     * UC07 — Calcula frete via AJAX/POST
     */
    public function calcularFrete()
    {
        $cep             = $this->request->getPost('cep') ?? '';
        $carrinhoUseCase = new GerenciarCarrinhoUseCase(session());
        $total           = $carrinhoUseCase->calcularTotal();

        $freteUseCase = new CalcularFreteComercialUseCase();
        $resultado    = $freteUseCase->calcular($cep, $total);

        // Salva CEP na sessão para exibir no carrinho
        session()->set('cep_frete', $cep);

        return $this->response->setJSON($resultado);
    }

    // Mostra a tela final de pagamento puxando os dados do cliente e do carrinho
    public function exibirCheckout(): string
    {
        $carrinhoUseCase = new GerenciarCarrinhoUseCase(session());

        if ($carrinhoUseCase->estaVazio()) {
            session()->setFlashdata('aviso', 'Seu carrinho está vazio. Adicione produtos primeiro.');
            return redirect()->to('/catalogo');
        }

        $id_usuario   = (int) session()->get('user_id');
        $clienteModel = new ClienteModel();
        $cliente      = $clienteModel->buscarComUsuario($id_usuario);

        return view('checkout/checkout', [
            'itens'   => $carrinhoUseCase->obterCarrinho(),
            'total'   => $carrinhoUseCase->calcularTotal(),
            'usuario' => $cliente, // objeto com nome, email, cpf, telefone, enderecos[]
        ]);
    }

    // Fecha a compra, processa pagamento e avisa a artesã
    public function finalizarPedido()
    {
        $id_usuario = (int) session()->get('user_id');
        $id_cliente = (int) session()->get('user_perfil_id');
        $post       = $this->request->getPost();

        // Busca o endereço principal do cliente
        $enderecoModel = new EnderecoModel();
        $endereco      = $enderecoModel->buscarPrincipal($id_cliente);

        if (!$endereco) {
            session()->setFlashdata('erro', 'Endereço de entrega não encontrado. Por favor, atualize seu cadastro.');
            return redirect()->to('/checkout');
        }

        $carrinhoUseCase = new GerenciarCarrinhoUseCase(session());
        $itensCarrinho   = $carrinhoUseCase->obterCarrinho();
        $metodoPagamento = $post['pagamento'] ?? 'pix';

        // Busca CPF para o mock de gateway
        $db     = \Config\Database::connect();
        $cpfRow = $db->table('clientes')->select('cpf')->where('id_cliente', $id_cliente)->get()->getRow();
        $cpf    = $cpfRow->cpf ?? '';

        try {
            $useCase = new CheckoutUseCase(
                new PedidoModel(),
                new ProdutoModel(),
                new ItemPedidoModel(),
                \Config\Database::connect()
            );

            $resultado = $useCase->executar(
                $id_cliente,
                $itensCarrinho,
                (int) $endereco->id_endereco,
                $metodoPagamento,
                $cpf
            );

            $carrinhoUseCase->limpar();
            session()->remove('cep_frete');

            $pedidoId = $resultado['pedido_id'];
            $pgStatus = $resultado['pagamento']['status'] ?? 'pendente';

            if ($pgStatus === 'aprovado') {
                $msg = "✅ Pedido #{$pedidoId} registrado e pagamento aprovado!";
            } elseif ($pgStatus === 'pendente') {
                $msg = "Pedido #{$pedidoId} criado! Veja o Pix em Meus Pedidos e pague diretamente à artesã.";
            } else {
                $msg = "Pedido #{$pedidoId} registrado, mas o pagamento foi recusado.";
            }

            session()->setFlashdata('sucesso', $msg);
            return redirect()->to('/cliente/pedidos');

        } catch (\RuntimeException $e) {
            session()->setFlashdata('erro', $e->getMessage());
            return redirect()->to('/checkout');
        }
    }

    public function dashboardCliente(): string
    {
        $id_cliente = (int) session()->get('user_perfil_id');
        $pedidos    = (new PedidoModel())->listarPorCliente($id_cliente);

        return view('cliente/dashboard', [
            'usuario' => (object) [
                'nome'  => session()->get('user_nome'),
                'email' => session()->get('user_email'),
            ],
            'pedidos' => array_slice($pedidos, 0, 5),
        ]);
    }

    public function meusPedidos(): string
    {
        $id_cliente = (int) session()->get('user_perfil_id');
        $pedidos    = (new PedidoModel())->listarPorCliente($id_cliente);

        return view('cliente/pedidos', ['pedidos' => $pedidos]);
    }

    /**
     * UC11 — Rastrear encomenda pelo ID do pedido
     */
    public function rastrearPedido(int $id): string
    {
        try {
            $useCase   = new RastrearEncomendaUseCase(new PedidoModel());
            $rastreio  = $useCase->rastrearPorId($id);
        } catch (\RuntimeException $e) {
            session()->setFlashdata('erro', $e->getMessage());
            return redirect()->to('/cliente/pedidos');
        }

        return view('cliente/rastreio', ['rastreio' => $rastreio]);
    }
}
