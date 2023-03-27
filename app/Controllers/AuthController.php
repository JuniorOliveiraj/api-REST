<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use App\Models\UserModel;


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
class AuthController extends ResourceController
{
    // Chave secreta para gerar tokens JWT
    private $key = "$2y$10MFKDgDBujKwY.VZi/DH6JuR58ISGjlS6mlEobHlmhX9zQ.Ha4c3qC2";

    // Endpoint de login
    public function login()
    {
        // Configurar as headers de CORS para permitir todas as origens
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        // Verificar se a requisição é do tipo OPTIONS
        // Obter as credenciais do usuário do corpo da solicitação POST
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
      
        if (!$email) {
            return $this->failUnauthorized('email é obrigatorio');
        }
        if (!$password) {
            return $this->failUnauthorized('senha obrigatoria');
        }
        // Verificar se as credenciais são válidas
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();
        if (!$user || !password_verify($password, $user['password'])) {
            return $this->failUnauthorized('Credenciais inválidas');
        } else {
            // Gerar um token de autenticação usando a biblioteca JWT
            $jwt = JWT::encode(['user_id' => $user['id']], $this->key, 'HS256');
            // Retornar o token como resposta
            return $this->respond(['token' => $jwt, 'user' => $user], 200);
        }

    }


    public function register()
    {
        // Configurar as headers de CORS para permitir todas as origens
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        // Obtenha os dados do usuário do corpo da solicitação POST
        $name = $this->request->getVar('name');
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        // Verifique se todos os campos foram preenchidos
        if (!$name || !$email || !$password) {
            return $this->failValidationError('Preencha todos os campos obrigatórios.');
        }

        // Criptografe a senha do usuário antes de salvar no banco de dados
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Salve o usuário no banco de dados
        $userModel = new UserModel();

        // Verifique se o email já está em uso
        if ($userModel->where('email', $email)->first()) {
            return $this->failValidationError('Este email já está em uso.');
        }

        $userData = [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword
        ];
        $userModel->insert($userData);
        $user = $userModel->where('email', $email)->first();
        // Retorne uma resposta de sucesso
        // Gerar um token de autenticação usando a biblioteca JWT
        $jwt = JWT::encode(['user_id' => $user['id']], $this->key, 'HS256');
        // Retornar o token como resposta
        return $this->respondCreated(['message' => 'Usuário criado com sucesso.', 'token' => $jwt, 'user' => $user], 200);
    }

}