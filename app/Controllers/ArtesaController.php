<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\ProdutoModel;
use App\Models\PedidoModel;
use App\Models\CategoriaModel;
use App\Models\ArtesaoModel;
use App\UseCases\CadastrarProdutoUseCase;
use App\UseCases\EditarProdutoUseCase;
use CodeIgniter\Controller;

class ArtesaController extends Controller
{
    // Puxa as métricas de vendas e produtos para montar a tela inicial da artesã
    public function dashboard(): string
    {
        $id_artesao   = (int) session()->get('user_perfil_id');
        $pedidoModel  = new PedidoModel();
        $produtoModel = new ProdutoModel();

        $metricas      = $pedidoModel->metricsArtesa($id_artesao);
        $totalProdutos = count($produtoModel->listarPorArtesa($id_artesao));

        return view('artesa/dashboard', [
            'usuario'          => (object) [
                'nome'  => session()->get('user_nome'),
                'email' => session()->get('user_email'),
            ],
            'totalProdutos'    => $totalProdutos,
            'totalPedidos'     => (int)   ($metricas->total_pedidos    ?? 0),
            'totalFaturado'    => (float)  ($metricas->total_faturado   ?? 0),
            'pedidosPendentes' => (int)   ($metricas->pedidos_pendentes ?? 0),
            'pedidosEntregues' => (int)   ($metricas->pedidos_entregues ?? 0),
        ]);
    }

    public function apoio(): string
    {
        return view('artesa/apoio', [
            'usuario' => (object) [
                'nome'  => session()->get('user_nome'),
                'email' => session()->get('user_email'),
            ],
        ]);
    }

    public function listarProdutos(): string
    {
        $id_artesao = (int) session()->get('user_perfil_id');
        $produtos   = (new ProdutoModel())->listarPorArtesa($id_artesao);

        return view('artesa/meus_produtos', ['produtos' => $produtos]);
    }

    public function exibirFormCriacao(): string
    {
        $categorias = (new CategoriaModel())->listarParaDropdown();

        return view('artesa/cadastro_produto', ['categorias' => $categorias]);
    }

    // Trata a foto enviada e salva o novo produto na loja
    public function criarProduto()
    {
        $id_artesao = (int) session()->get('user_perfil_id');
        $imagem     = $this->request->getFile('foto');

        $dados = [
            'categoria_id'    => $this->request->getPost('categoria_id'),
            'nome'            => $this->request->getPost('nome'),
            'descricao'       => $this->request->getPost('descricao'),
            'preco'           => $this->request->getPost('preco'),
            'prazo_confeccao' => $this->request->getPost('tipo') === 'pronta_entrega' ? 1 : $this->request->getPost('prazo_confeccao'),
            'estoque'         => $this->request->getPost('tipo') === 'sob_encomenda' ? 0 : $this->request->getPost('estoque'),
            'tipo'            => $this->request->getPost('tipo'),
        ];

        try {
            $useCase = new CadastrarProdutoUseCase(new ProdutoModel());
            $useCase->executar($dados, $id_artesao, $imagem);

            session()->setFlashdata('sucesso', '🧶 Produto publicado com sucesso na sua vitrine!');
            return redirect()->to('/artesa/produtos');

        } catch (\RuntimeException $e) {
            session()->setFlashdata('erro', $e->getMessage());
            return redirect()->to('/artesa/produtos/criar');
        }
    }

    public function exibirFormEdicao(int $id): string
    {
        $id_artesao   = (int) session()->get('user_perfil_id');
        $produtoModel = new ProdutoModel();
        $produto      = $produtoModel->buscarParaEdicao($id, $id_artesao);

        if ($produto === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Produto não encontrado ou você não tem permissão para editá-lo.'
            );
        }

        $categorias = (new CategoriaModel())->listarParaDropdown();

        return view('artesa/editar_produto', [
            'produto'    => $produto,
            'categorias' => $categorias,
        ]);
    }

    // Edita os dados e a foto do produto, garantindo que ele pertence à artesã logada
    public function atualizarProduto(int $id)
    {
        $id_artesao = (int) session()->get('user_perfil_id');
        $imagem     = $this->request->getFile('foto');

        $dados = [
            'categoria_id'    => $this->request->getPost('categoria_id'),
            'nome'            => $this->request->getPost('nome'),
            'descricao'       => $this->request->getPost('descricao'),
            'preco'           => $this->request->getPost('preco'),
            'prazo_confeccao' => $this->request->getPost('tipo') === 'pronta_entrega' ? 1 : $this->request->getPost('prazo_confeccao'),
            'estoque'         => $this->request->getPost('tipo') === 'sob_encomenda' ? 0 : $this->request->getPost('estoque'),
            'tipo'            => $this->request->getPost('tipo'),
        ];

        try {
            $useCase = new EditarProdutoUseCase(new ProdutoModel());
            $useCase->executar($id, $dados, $id_artesao, $imagem);

            session()->setFlashdata('sucesso', 'Produto atualizado com sucesso!');
            return redirect()->to('/artesa/produtos');

        } catch (\RuntimeException $e) {
            session()->setFlashdata('erro', $e->getMessage());
            return redirect()->to("/artesa/produtos/editar/{$id}");
        }
    }

    // Remove o produto da vitrine
    public function excluirProduto(int $id)
    {
        $id_artesao = (int) session()->get('user_perfil_id');

        try {
            $useCase = new EditarProdutoUseCase(new ProdutoModel());
            $useCase->excluir($id, $id_artesao);

            session()->setFlashdata('sucesso', 'Produto removido da vitrine com sucesso.');

        } catch (\RuntimeException $e) {
            session()->setFlashdata('erro', $e->getMessage());
        }

        return redirect()->to('/artesa/produtos');
    }

    public function listarPedidos(): string
    {
        $id_artesao = (int) session()->get('user_perfil_id');
        $pedidos    = (new PedidoModel())->listarPorArtesa($id_artesao);

        return view('artesa/pedidos', ['pedidos' => $pedidos]);
    }

    // Muda a etapa do pedido (ex: de 'em produção' para 'enviado aos correios')
    public function atualizarStatusPedido(int $id)
    {
        $novoStatus  = $this->request->getPost('status_entrega');
        $pedidoModel = new PedidoModel();

        $pedido = $pedidoModel->find($id);
        if ($pedido === null || (int) $pedido->id_artesao !== (int) session()->get('user_perfil_id')) {
            session()->setFlashdata('erro', 'Ação não autorizada. (RN15)');
            return redirect()->to('/artesa/pedidos');
        }

        // Atualiza código de rastreio se enviado
        $codigoRastreio = trim($this->request->getPost('codigo_rastreio') ?? '');
        $updateData     = ['status_entrega' => $novoStatus];

        if ($codigoRastreio !== '') {
            $updateData['codigo_rastreio'] = $codigoRastreio;
        }

        if (in_array($novoStatus, ['em_producao', 'aguardando_coleta', 'enviado', 'entregue', 'cancelado'], true)) {
            $pedidoModel->update($id, $updateData);
            session()->setFlashdata('sucesso', 'Status do pedido atualizado com sucesso!');
        } else {
            session()->setFlashdata('erro', 'Status inválido. Tente novamente.');
        }

        return redirect()->to('/artesa/pedidos');
    }

    public function relatorio(): string
    {
        $id_artesao = (int) session()->get('user_perfil_id');
        $pedidos    = (new PedidoModel())->listarPorArtesa($id_artesao);
        $metricas   = (new PedidoModel())->metricsArtesa($id_artesao);

        return view('artesa/relatorio_vendas', [
            'pedidos'  => $pedidos,
            'metricas' => $metricas,
        ]);
    }
}
