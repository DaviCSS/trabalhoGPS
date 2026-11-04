<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\ProdutoModel;
use CodeIgniter\HTTP\Files\UploadedFile;

class CadastrarProdutoUseCase
{
    use UploadImagemTrait;

    private const UPLOAD_DIR = 'uploads/produtos';

    public function __construct(
        private readonly ProdutoModel $produtoModel
    ) {}

    public function executar(array $dados, int $artesaoId, ?UploadedFile $imagem = null): int
    {

        $dados['artesao_id'] = $artesaoId;

        if ($imagem !== null && $imagem->isValid() && !$imagem->hasMoved()) {
            $dados['imagem_path'] = $this->processarUpload($imagem);
        }

        $id = $this->produtoModel->insert($dados, true);

        if ($id === false) {
            $erros     = $this->produtoModel->errors();
            $mensagem  = implode(' | ', $erros);
            throw new \RuntimeException($mensagem);
        }

        return (int) $id;
    }
}
