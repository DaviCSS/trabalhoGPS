<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model para controle de tentativas de login (RNF06).
 * Implementa o bloqueio temporário após 5 tentativas falhas.
 */
class LoginTentativaModel extends Model
{
    protected $table      = 'login_tentativas';
    protected $primaryKey = 'id';
    protected $returnType = 'object';

    protected $allowedFields = [
        'email',
        'tentativas',
        'bloqueado_ate',
        'updated_at',
    ];

    protected $useTimestamps = false;

    /**
     * Verifica se o email está bloqueado no momento.
     */
    public function estaBloqueado(string $email): bool
    {
        $registro = $this->where('email', $email)->first();

        if (!$registro) {
            return false;
        }

        if ($registro->bloqueado_ate === null) {
            return false;
        }

        return strtotime($registro->bloqueado_ate) > time();
    }

    /**
     * Retorna em quantos minutos o bloqueio expira.
     */
    public function minutosParaDesbloqueio(string $email): int
    {
        $registro = $this->where('email', $email)->first();
        if (!$registro || !$registro->bloqueado_ate) {
            return 0;
        }

        $diff = strtotime($registro->bloqueado_ate) - time();
        return max(0, (int) ceil($diff / 60));
    }

    /**
     * Registra uma tentativa de login falha.
     * Bloqueia por 15 minutos ao atingir 5 tentativas consecutivas.
     */
    public function registrarFalha(string $email): void
    {
        $registro = $this->where('email', $email)->first();
        $agora    = date('Y-m-d H:i:s');

        if (!$registro) {
            $this->db->table('login_tentativas')->insert([
                'email'        => $email,
                'tentativas'   => 1,
                'bloqueado_ate' => null,
                'updated_at'   => $agora,
            ]);
            return;
        }

        $novasTentativas = (int) $registro->tentativas + 1;
        $bloqueadoAte    = null;

        if ($novasTentativas >= 5) {
            // Bloqueia por 15 minutos
            $bloqueadoAte = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        }

        $this->db->table('login_tentativas')
            ->where('email', $email)
            ->update([
                'tentativas'   => $novasTentativas,
                'bloqueado_ate' => $bloqueadoAte,
                'updated_at'   => $agora,
            ]);
    }

    /**
     * Zera as tentativas de login após autenticação bem-sucedida.
     */
    public function registrarSucesso(string $email): void
    {
        $this->db->table('login_tentativas')
            ->where('email', $email)
            ->update([
                'tentativas'    => 0,
                'bloqueado_ate' => null,
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
    }
}
