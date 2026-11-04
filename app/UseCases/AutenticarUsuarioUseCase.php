<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Models\UsuarioModel;
use App\Models\LoginTentativaModel;
use CodeIgniter\Session\Session;

/**
 * UC01 — Autenticar Usuário
 * RNF06 — Bloqueio após 5 tentativas inválidas consecutivas (15 min)
 */
class AutenticarUsuarioUseCase
{
    public function __construct(
        private readonly UsuarioModel        $usuarioModel,
        private readonly Session             $session,
        private readonly LoginTentativaModel $tentativaModel = new LoginTentativaModel()
    ) {}

    public function login(string $email, string $senha): object
    {
        $email = strtolower(trim($email));

        // RNF06 — Verifica bloqueio antes de qualquer consulta
        if ($this->tentativaModel->estaBloqueado($email)) {
            $minutos = $this->tentativaModel->minutosParaDesbloqueio($email);
            throw new \RuntimeException(
                "Conta temporariamente bloqueada por excesso de tentativas. " .
                "Tente novamente em aproximadamente {$minutos} minuto(s). (RNF06)"
            );
        }

        // Tenta autenticar
        $usuario = $this->usuarioModel->autenticar($email, $senha);

        if ($usuario === null) {
            // RNF06 — Registra falha e verifica se deve bloquear
            $this->tentativaModel->registrarFalha($email);

            if ($this->tentativaModel->estaBloqueado($email)) {
                throw new \RuntimeException(
                    'Conta bloqueada temporariamente após 5 tentativas incorretas. ' .
                    'Aguarde 15 minutos antes de tentar novamente. (RNF06)'
                );
            }

            throw new \RuntimeException('E-mail ou senha incorretos. Verifique suas credenciais.');
        }

        // Sucesso — Zera tentativas
        $this->tentativaModel->registrarSucesso($email);

        // Identifica o perfil (cliente / artesao / funcionario)
        $perfil = $this->usuarioModel->buscarPerfilCompleto((int) $usuario->id_usuario);

        $this->session->regenerate(true);

        $this->session->set([
            'logado'          => true,
            'user_id'         => (int) $usuario->id_usuario,
            'user_nome'       => $usuario->nome,
            'user_email'      => $usuario->email,
            // Mantém flags de compatibilidade com os Filters existentes
            'user_is_artesa'  => ($perfil !== null && $perfil->tipo === 'artesao'),
            'user_is_admin'   => ($perfil !== null && $perfil->tipo === 'funcionario'),
            // Novos campos arquiteturais
            'user_tipo'       => $perfil?->tipo ?? 'cliente',
            'user_perfil_id'  => match ($perfil?->tipo ?? 'cliente') {
                'artesao'     => (int) ($perfil->id_artesao ?? 0),
                'funcionario' => (int) ($perfil->id_funcionario ?? 0),
                default       => (int) ($perfil->id_cliente ?? 0),
            },
        ]);

        return $usuario;
    }

    public function logout(): void
    {
        $this->session->destroy();
    }

    public function estaLogado(): bool
    {
        return (bool) $this->session->get('logado');
    }

    public function eArtesa(): bool
    {
        return (bool) $this->session->get('user_is_artesa');
    }
}
