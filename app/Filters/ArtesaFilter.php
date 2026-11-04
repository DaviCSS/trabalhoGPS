<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ArtesaFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {

        if (!session()->get('logado')) {
            session()->setFlashdata('aviso', 'Você precisa estar logada para acessar esta página.');
            return redirect()->to('/login');
        }

        if (!session()->get('user_is_artesa')) {
            session()->setFlashdata('erro', 'Acesso restrito ao painel de artesãs.');
            return redirect()->to('/cliente/dashboard');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}
