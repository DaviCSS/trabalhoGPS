<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{

    protected $table      = 'categorias';
    protected $primaryKey = 'id';
    protected $returnType = 'object';

    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'nome',
        'slug',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nome' => [
            'label'  => 'Nome da Categoria',
            'rules'  => 'required|min_length[2]|max_length[50]|is_unique[categorias.nome,id,{id}]',
            'errors' => [
                'required'   => 'O nome da categoria é obrigatório.',
                'min_length' => 'O nome da categoria deve ter pelo menos 2 caracteres.',
                'max_length' => 'O nome da categoria não pode ultrapassar 50 caracteres.',
                'is_unique'  => 'Esta categoria já existe no sistema.',
            ],
        ],
    ];

    public function listarTodasOrdenadas(): array
    {
        return $this->orderBy('nome', 'ASC')->findAll();
    }

    public function listarParaDropdown(): array
    {
        $categorias = $this->listarTodasOrdenadas();
        $dropdown   = [];

        foreach ($categorias as $categoria) {
            $dropdown[$categoria->id] = $categoria->nome;
        }

        return $dropdown;
    }
}
