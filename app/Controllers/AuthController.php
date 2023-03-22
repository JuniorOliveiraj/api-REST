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
    private $key = "sua_chave_secreta_aqui";

    // Endpoint de login
    public function login()
    {
        // Configurar as headers de CORS para permitir todas as origens
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        // Verificar se a requisição é do tipo OPTIONS
        if ($this->request->getMethod() === 'options') {
            return $this->response->setHeader('Access-Control-Allow-Methods', '*');
        }

        // Obter as credenciais do usuário do corpo da solicitação POST
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Verificar se as credenciais são válidas
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();
        if (!$user || !password_verify($password, $user['password'])) {
            return $this->failUnauthorized('Credenciais inválidas');
        }

        // Gerar um token de autenticação usando a biblioteca JWT
        $jwt = JWT::encode(['user_id' => $user['id']], $this->key, 'HS256');

        // Retornar o token como resposta
        return $this->respond(['token' => $jwt], 200);
    }
}
