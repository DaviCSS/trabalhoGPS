<?php

declare(strict_types=1);

namespace App\Models;

use App\Entities\Produto;
use CodeIgniter\Model;

class ProdutoModel extends Model
{
    protected $table      = 'produtos';
    protected $primaryKey = 'id';
    // Retorna instâncias da Entidade de Domínio, com regras de negócio embutidas (Clean Architecture)
    protected $returnType = Produto::class;

    // Oculta o produto em vez de apagar do banco para não quebrar o histórico de vendas
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'artesao_id',
        'categoria_id',
        'nome',
        'descricao',
        'preco',
        'prazo_confeccao',
        'estoque',
        'tipo',
        'imagem_path',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'artesao_id' => [
            'label'  => 'Artesã',
            // FK agora aponta para artesaos.id_artesao
            'rules'  => 'required|integer|is_not_unique[artesaos.id_artesao]',
            'errors' => [
                'required'      => 'O produto deve estar vinculado a uma artesã.',
                'integer'       => 'ID de artesã inválido.',
                'is_not_unique' => 'A artesã informada não existe no sistema.',
            ],
        ],
        'categoria_id' => [
            'label'  => 'Categoria',
            'rules'  => 'required|integer|is_not_unique[categorias.id]',
            'errors' => [
                'required'      => 'Selecione uma categoria para o produto.',
                'integer'       => 'ID de categoria inválido.',
                'is_not_unique' => 'A categoria informada não existe no sistema.',
            ],
        ],
        'nome' => [
            'label'  => 'Nome do Produto',
            'rules'  => 'required|min_length[3]|max_length[200]',
            'errors' => [
                'required'   => 'O nome do produto é obrigatório.',
                'min_length' => 'O nome deve ter pelo menos 3 caracteres.',
                'max_length' => 'O nome não pode ultrapassar 200 caracteres.',
            ],
        ],
        'preco' => [
            'label'  => 'Preço',
            'rules'  => 'required|decimal|greater_than[0]',
            'errors' => [
                'required'     => 'O preço do produto é obrigatório.',
                'decimal'      => 'Informe um preço válido (ex: 29.90).',
                'greater_than' => 'O preço deve ser maior que zero.',
            ],
        ],
        'prazo_confeccao' => [
            'label'  => 'Prazo de Confecção (dias)',
            'rules'  => 'required|integer|greater_than_equal_to[1]',
            'errors' => [
                'required'              => 'O prazo de confecção é obrigatório. (RN07)',
                'integer'               => 'O prazo deve ser um número inteiro de dias.',
                'greater_than_equal_to' => 'O prazo mínimo é de 1 dia.',
            ],
        ],
        'estoque' => [
            'label'  => 'Estoque',
            'rules'  => 'required|integer|greater_than_equal_to[0]',
            'errors' => [
                'required'              => 'A quantidade em estoque é obrigatória.',
                'integer'               => 'O estoque deve ser um número inteiro.',
                'greater_than_equal_to' => 'O estoque não pode ser negativo.',
            ],
        ],
        'tipo' => [
            'label'  => 'Tipo de Produto',
            'rules'  => 'required|in_list[pronta_entrega,sob_encomenda,misto]',
            'errors' => [
                'required' => 'Selecione o tipo do produto.',
                'in_list'  => 'Tipo inválido. Escolha uma das opções.',
            ],
        ],
    ];

    /**
     * Busca os produtos prontos para a vitrine, fazendo JOIN com artesaos→usuarios e categorias.
     */
    public function listarParaVitrine(?int $categoriaId = null, ?int $limit = null): array
    {
        $builder = $this->db->table('produtos p')
            ->select('p.*, c.nome AS categoria_nome, u.nome AS artesa_nome')
            ->join('categorias c',    'c.id = p.categoria_id',             'left')
            ->join('artesaos a',      'a.id_artesao = p.artesao_id',       'left')
            ->join('usuarios u',      'u.id_usuario = a.id_usuario',       'left')
            ->where('p.deleted_at IS NULL');

        if ($categoriaId !== null) {
            $builder->where('p.categoria_id', $categoriaId);
        }

        if ($limit !== null) {
            $builder->limit($limit);
        }

        return $builder->orderBy('p.created_at', 'DESC')->get()->getResultObject();
    }

    public function buscarComDetalhes(int $id): ?object
    {
        $resultado = $this->db->table('produtos p')
            ->select('p.*, c.nome AS categoria_nome, u.nome AS artesa_nome, u.telefone AS artesa_telefone, a.id_artesao')
            ->join('categorias c', 'c.id = p.categoria_id',          'left')
            ->join('artesaos a',   'a.id_artesao = p.artesao_id',    'left')
            ->join('usuarios u',   'u.id_usuario = a.id_usuario',    'left')
            ->where('p.id', $id)
            ->where('p.deleted_at IS NULL')
            ->get()
            ->getRowObject();

        return $resultado ?: null;
    }

    public function listarPorArtesa(int $artesaoId, bool $incluirDeletados = false): array
    {
        if ($incluirDeletados) {
            return $this->withDeleted()
                         ->where('artesao_id', $artesaoId)
                         ->orderBy('created_at', 'DESC')
                         ->findAll();
        }

        return $this->where('artesao_id', $artesaoId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function buscarParaEdicao(int $produtoId, int $artesaoId): ?object
    {
        return $this->where('id', $produtoId)
                    ->where('artesao_id', $artesaoId)
                    ->first();
    }

    // Tira do estoque a quantidade que acabou de ser vendida
    public function decrementarEstoque(int $produtoId, int $quantidade): bool
    {
        $this->db->query(
            'UPDATE produtos SET estoque = estoque - ?, updated_at = NOW() WHERE id = ? AND estoque >= ?',
            [$quantidade, $produtoId, $quantidade]
        );

        return $this->db->affectedRows() > 0;
    }

    // Confere se ainda tem a peça no estoque ou se ela aceita ser feita sob encomenda
    public function verificarDisponibilidade(object $produto, int $quantidadeDesejada = 1): bool
    {
        if (in_array($produto->tipo, ['sob_encomenda', 'misto'])) {
            return true;
        }

        return $produto->estoque >= $quantidadeDesejada;
    }
}
