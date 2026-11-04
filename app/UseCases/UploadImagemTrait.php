<?php

declare(strict_types=1);

namespace App\UseCases;

use CodeIgniter\HTTP\Files\UploadedFile;

trait UploadImagemTrait
{

    private const UPLOAD_DIR = 'uploads/produtos';

    private function processarUpload(UploadedFile $imagem): string
    {

        $mimesPermitidos = ['image/jpg', 'image/jpeg', 'image/png'];

        if (!in_array($imagem->getMimeType(), $mimesPermitidos, true)) {
            throw new \RuntimeException(
                'Formato de imagem inválido. Envie apenas arquivos JPG ou PNG. (RN10)'
            );
        }

        if ($imagem->getSizeByUnit('kb') > 2048) {
            throw new \RuntimeException(
                'A imagem não pode ultrapassar 2 MB (2048 KB). (RN10)'
            );
        }

        $pastaDestino = FCPATH . self::UPLOAD_DIR;

        $nomeArquivo = $imagem->getRandomName();

        if (!$imagem->move($pastaDestino, $nomeArquivo)) {
            throw new \RuntimeException(
                'Erro ao salvar a imagem no servidor. Tente novamente.'
            );
        }

        return self::UPLOAD_DIR . '/' . $nomeArquivo;
    }

    private function removerImagemAntiga(?string $imagemPath): void
    {
        if (empty($imagemPath)) {
            return;
        }

        $caminhoAbsoluto = FCPATH . $imagemPath;

        if (is_file($caminhoAbsoluto)) {
            unlink($caminhoAbsoluto);
        }
    }
}
