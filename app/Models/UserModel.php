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

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
}
