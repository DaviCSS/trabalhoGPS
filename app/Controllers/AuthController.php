<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\ClienteModel;
use App\Models\ArtesaoModel;
use App\UseCases\CadastrarUsuarioUseCase;
use App\UseCases\AutenticarUsuarioUseCase;
use App\Models\LoginTentativaModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function exibirLogin()
    {
        if (session()->get('logado')) {
            return $this->redirecionarPorTipo();
        }

        return view('auth/login');
    }

    // Tenta achar o usuário por e-mail ou CPF e faz o login
    public function processarLogin()
    {
        $usuarioModel = new UsuarioModel();
        $useCase      = new AutenticarUsuarioUseCase(
            $usuarioModel,
            session(),
            new LoginTentativaModel()
        );

        $identificador = trim($this->request->getPost('identificador') ?? '');
        $senha         = $this->request->getPost('senha') ?? '';

        $email = filter_var($identificador, FILTER_VALIDATE_EMAIL)
            ? $identificador
            : null;

        // Login por CPF — busca o e-mail na tabela clientes
        if ($email === null) {
            $cpfLimpo = preg_replace('/\D/', '', $identificador);
            $usuario  = $usuarioModel->buscarPorCpf($cpfLimpo);

            if ($usuario === null) {
                session()->setFlashdata('erro', 'Usuário não encontrado. Verifique seu CPF ou e-mail.');
                return redirect()->to('/login')->withInput();
            }

            $email = $usuario->email;
        }

        try {
            $useCase->login($email, $senha);
            return $this->redirecionarPorTipo();

        } catch (\RuntimeException $e) {
            session()->setFlashdata('erro', $e->getMessage());
            return redirect()->to('/login')->withInput();
        }
    }

    // Encerra a sessão e desloga o usuário
    public function logout()
    {
        $useCase = new AutenticarUsuarioUseCase(
            new UsuarioModel(),
            session(),
            new LoginTentativaModel()
        );
        $useCase->logout();
        session()->setFlashdata('sucesso', 'Você saiu da sua conta com segurança.');
        return redirect()->to('/');
    }

    public function exibirCadastroCliente()
    {
        return view('auth/cadastro_cliente');
    }

    public function cadastrarCliente()
    {
        return $this->processarCadastro(isArtesa: false);
    }

    public function exibirCadastroArtesa()
    {
        return view('auth/cadastro_artesa');
    }

    public function cadastrarArtesa()
    {
        return $this->processarCadastro(isArtesa: true);
    }

    // Confere se as senhas batem e salva o novo cadastro no banco (múltiplas tabelas)
    private function processarCadastro(bool $isArtesa)
    {
        $post = $this->request->getPost();

        if ($post['senha'] !== $post['confirmarSenha']) {
            session()->setFlashdata('erro', 'As senhas informadas não coincidem. Tente novamente.');
            $destino = $isArtesa ? '/cadastro/artesa' : '/cadastro/cliente';
            return redirect()->to($destino)->withInput();
        }

        // Monta os dados incluindo os campos de endereço separados (para CadastrarUsuarioUseCase)
        $dados = [
            'nome'        => $post['nome']      ?? '',
            'cpf'         => $post['cpf']        ?? '',
            'telefone'    => $post['telefone']   ?? '',
            'email'       => $post['email']      ?? '',
            'senha'       => $post['senha']      ?? '',
            // Campos de endereço (apenas para clientes)
            'rua'         => $post['rua']        ?? '',
            'numero'      => $post['numero']     ?? '',
            'complemento' => $post['complemento'] ?? '',
            'bairro'      => $post['bairro']     ?? '',
            'cidade'      => $post['cidade']     ?? '',
            'estado'      => $post['estado']     ?? '',
            'cep'         => $post['cep']        ?? '',
        ];

        try {
            $useCase = new CadastrarUsuarioUseCase(new UsuarioModel());
            $useCase->executar($dados, $isArtesa);

            $perfil  = $isArtesa ? 'artesã' : 'cliente';
            session()->setFlashdata('sucesso', "Cadastro de {$perfil} realizado! Faça login para continuar.");
            return redirect()->to('/login');

        } catch (\RuntimeException $e) {
            $destino = $isArtesa ? '/cadastro/artesa' : '/cadastro/cliente';
            session()->setFlashdata('erro', $e->getMessage());
            return redirect()->to($destino)->withInput();
        }
    }

    public function recuperarSenha()
    {
        return view('auth/recuperar_senha');
    }

    // Cria um token de segurança e simula o envio do link para redefinir senha
    public function processarRecuperarSenha()
    {
        $identificador = trim($this->request->getPost('identificador') ?? '');
        $usuarioModel  = new UsuarioModel();

        $email = filter_var($identificador, FILTER_VALIDATE_EMAIL)
            ? $identificador
            : null;

        if ($email === null) {
            $cpfLimpo = preg_replace('/\D/', '', $identificador);
            $usuario  = $usuarioModel->buscarPorCpf($cpfLimpo);
        } else {
            $usuario = $usuarioModel->buscarPorEmail($email);
        }

        if ($usuario) {
            $token     = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $usuarioModel->update($usuario->id_usuario, [
                'reset_token'      => $token,
                'reset_expires_at' => $expiresAt,
            ]);

            $link = base_url('redefinir-senha/' . $token);
            session()->setFlashdata('sucesso', "Instruções enviadas! (MVP Demo: <a href='{$link}' class='alert-link'>Clique aqui para redefinir</a>)");
        } else {
            session()->setFlashdata('sucesso', 'Se o e-mail/CPF existir no sistema, você receberá as instruções em breve.');
        }

        return redirect()->to('/login');
    }

    public function exibirNovaSenha(string $token)
    {
        $usuarioModel = new UsuarioModel();
        $usuario      = $usuarioModel->where('reset_token', $token)
                                     ->where('reset_expires_at >=', date('Y-m-d H:i:s'))
                                     ->first();

        if (!$usuario) {
            session()->setFlashdata('erro', 'O link de redefinição é inválido ou expirou.');
            return redirect()->to('/login');
        }

        return view('auth/nova_senha', ['token' => $token]);
    }

    // Salva a nova senha se o token do link ainda for válido e as senhas baterem
    public function processarNovaSenha(string $token)
    {
        $senha          = $this->request->getPost('senha');
        $confirmarSenha = $this->request->getPost('confirmarSenha');

        if ($senha !== $confirmarSenha) {
            session()->setFlashdata('erro', 'As senhas não coincidem.');
            return redirect()->back();
        }

        $usuarioModel = new UsuarioModel();
        $usuario      = $usuarioModel->where('reset_token', $token)
                                     ->where('reset_expires_at >=', date('Y-m-d H:i:s'))
                                     ->first();

        if (!$usuario) {
            session()->setFlashdata('erro', 'O link de redefinição é inválido ou expirou.');
            return redirect()->to('/login');
        }

        if ($usuarioModel->update($usuario->id_usuario, [
            'senha'            => $senha,
            'reset_token'      => null,
            'reset_expires_at' => null,
        ])) {
            session()->setFlashdata('sucesso', 'Sua senha foi redefinida com sucesso! Você já pode fazer login.');
            return redirect()->to('/login');
        } else {
            session()->setFlashdata('erro', 'Erro ao atualizar a senha. A senha deve ter no mínimo 6 caracteres.');
            return redirect()->back();
        }
    }

    // --- Helpers ---

    private function redirecionarPorTipo()
    {
        $tipo = session()->get('user_tipo') ?? '';

        return match ($tipo) {
            'funcionario' => redirect()->to('/admin/dashboard'),
            'artesao'     => redirect()->to('/artesa/dashboard'),
            default       => redirect()->to('/cliente/dashboard'),
        };
    }
}
