<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\ProdutoModel;
use CodeIgniter\HTTP\Files\UploadedFile;

class EditarProdutoUseCase
{
    use UploadImagemTrait;
    private const UPLOAD_DIR = 'uploads/produtos';

    public function __construct(
        private readonly ProdutoModel $produtoModel
    ) {}

    public function executar(
        int $produtoId,
        array $dados,
        int $artesaoId,
        ?UploadedFile $imagem = null
    ): bool {

        $produto = $this->produtoModel->buscarParaEdicao($produtoId, $artesaoId);

        if ($produto === null) {
            throw new \RuntimeException(
                'Ação não autorizada. Você não tem permissão para editar este produto. (RN15)'
            );
        }

        unset($dados['artesao_id']);

        if ($imagem !== null && $imagem->isValid() && !$imagem->hasMoved()) {

            $this->removerImagemAntiga($produto->imagem_path);
            $dados['imagem_path'] = $this->processarUpload($imagem);
        }

        $resultado = $this->produtoModel->update($produtoId, $dados);

        if ($resultado === false) {
            $erros    = $this->produtoModel->errors();
            $mensagem = implode(' | ', $erros);
            throw new \RuntimeException($mensagem);
        }

        return true;
    }

    public function excluir(int $produtoId, int $artesaoId): bool
    {

        $produto = $this->produtoModel->buscarParaEdicao($produtoId, $artesaoId);

        if ($produto === null) {
            throw new \RuntimeException(
                'Ação não autorizada. Você não pode excluir este produto. (RN15)'
            );
        }

        return $this->produtoModel->delete($produtoId);
    }
}
