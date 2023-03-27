<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name', 'email', 'password'];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    protected $validationRules = [
        'name' => 'required',
        'email' => 'required|valid_email|is_unique[users.email]',
        'password' => 'required'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'O nome do usuário é obrigatório.'
        ],
        'email' => [
            'required' => 'O endereço de email é obrigatório.',
            'valid_email' => 'O endereço de email não é válido.',
            'is_unique' => 'Já existe um usuário cadastrado com este endereço de email.'
        ],
        'password' => [
            'required' => 'A senha é obrigatória.'
        ]
    ];

    /**
     * Cria um novo usuário.
     *
     * @param string $name     O nome do usuário.
     * @param string $email    O endereço de email do usuário.
     * @param string $password A senha do usuário.
     *
     * @return int|false O ID do novo usuário, ou false se houver um erro.
     */
    public function createNewUser($name, $email, $password)
    {
        // Validar os campos obrigatórios
        if (empty($name) || empty($email) || empty($password)) {
            return false;
        }
        // Criptografar a senha
        $password = password_hash($password, PASSWORD_DEFAULT);
        // Salvar o novo usuário no banco de dados
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ];
        $this->insert($data);
        // Retornar o ID do novo usuário
        return $this->insertID();
    }
    /**
     * Retorna um usuário com base no endereço de email e senha.
     *
     * @param string $email    O endereço de email do usuário.
     * @param string $password A senha do usuário.
     *
     * @return array|false O usuário encontrado, ou false se não houver correspondência.
     */
    public function getUserByEmail($email, $password)
    {
        $user = $this->where('email', $email)->first();
        if (!$user) {
            return false;
        }
        if (!password_verify($password, $user['password'])) {
            return false;
        }
        return $user;
    }
}
