<?php

declare(strict_types=1);

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\UsuarioModel;

class CreateAdmin extends BaseCommand
{
    /**
     * O grupo sob o qual o comando é agrupado
     *
     * @var string
     */
    protected $group = 'Admin';

    /**
     * O nome do comando
     *
     * @var string
     */
    protected $name = 'make:admin';

    /**
     * A descrição do comando
     *
     * @var string
     */
    protected $description = 'Cria um novo usuário Super Administrador da plataforma.';

    /**
     * Argumentos que o comando aceita
     *
     * @var array
     */
    protected $arguments = [
        'email' => 'O e-mail do administrador.',
        'senha' => 'A senha do administrador (mínimo 6 caracteres).',
    ];

    /**
     * Executa o comando
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $email = $params[0] ?? CLI::prompt('Email do Admin', null, 'required|valid_email');
        $senha = $params[1] ?? CLI::prompt('Senha', null, 'required|min_length[6]');

        $model = new UsuarioModel();

        // Checa se já existe
        $existe = $model->where('email', $email)->first();
        if ($existe) {
            CLI::error("O e-mail '{$email}' já está cadastrado.");
            return;
        }

        // Criamos um CPF fictício para preencher a RN01 (Obrigatório) no model
        // Um admin geralmente não precisa comprar, mas a tabela exige
        $cpfAdmin = str_pad((string) rand(11111111111, 99999999999), 11, '0', STR_PAD_LEFT);

        $dados = [
            'nome' => 'Super Administrador',
            'cpf' => $cpfAdmin,
            'telefone' => '00000000000',
            'email' => $email,
            'senha' => $senha,
            'endereco_completo' => 'Sede Crochettei',
            'is_artesa' => 0,
            'is_admin' => 1
        ];

        try {
            if ($model->insert($dados)) {
                CLI::write("Administrador '{$email}' criado com sucesso!", 'green');
            } else {
                CLI::error("Falha ao criar o administrador.");
                foreach ($model->errors() as $erro) {
                    CLI::error($erro);
                }
            }
        } catch (\Exception $e) {
            CLI::error('Erro: ' . $e->getMessage());
        }
    }
}
