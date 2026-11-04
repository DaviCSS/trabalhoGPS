<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('logado')) {
            $session->setFlashdata('aviso', 'Você precisa fazer login para acessar o painel administrativo.');
            return redirect()->to('/login');
        }

        if (!$session->get('user_is_admin')) {
            $session->setFlashdata('erro', 'Acesso negado. Esta área é restrita a administradores.');

            return redirect()->to(
                $session->get('user_is_artesa') ? '/artesa/dashboard' : '/cliente/dashboard'
            );
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}
