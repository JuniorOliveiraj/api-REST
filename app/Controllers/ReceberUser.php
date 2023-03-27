<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use App\Models\UserModel;
use CodeIgniter\Controller;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
class ReceberUser extends ResourceController
{
    private $key = "$2y$10MFKDgDBujKwY.VZi/DH6JuR58ISGjlS6mlEobHlmhX9zQ.Ha4c3qC2";
    public function createUser()
    {
        $request = $this->request->getJSON();


        // Extrai os dados do usuário da requisição


        $name = $this->request->getVar('name');
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        // Verifique se todos os campos foram preenchidos
        if (!$name || !$email || !$password) {
            return $this->respondCreated(['error' => 'Preencha todos os campos obrigatórios.', 'requisição' => $request], 500);
        }
        // Criptografe a senha do usuário antes de salvar no banco de dados
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Salve o usuário no banco de dados
        $userModel = new UserModel();

        // Verifique se o email já está em uso
        if ($userModel->where('email', $email)->first()) {
            return $this->respondCreated(['error' => 'Este email já está em uso.'], 500);
        }

        $userData = [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword
        ];
        $userModel->insert($userData);
        $user = $userModel->where('email', $email)->first();
        $jwt = JWT::encode(['user_id' => $user['id']], $this->key, 'HS256');

        // Aqui você pode realizar validações e armazenar os dados do usuário em um banco de dados, por exemplo


        return $this->respondCreated(['message' => 'Usuário criado com sucesso.', 'token' => $jwt, 'user' => $user], 200);
    }

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
            return $this->respond(['message' => 'email é obrigatorio.'], 200);
        }
        if (!$password) {
            return $this->respond(['message' => 'senha obrigatoria.'], 200);
        }
        // Verificar se as credenciais são válidas
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();
        if (!$user) {
            return $this->respond(['message' => 'email incorreto.'], 200);
        } elseif (!password_verify($password, $user['password'])) {
            return $this->respond(['message' => 'senha incorreto.'], 200);
        } else {
            // Gerar um token de autenticação usando a biblioteca JWT
            $jwt = JWT::encode(['user_id' => $user['id']], $this->key, 'HS256');
            // Retornar o token como resposta
            return $this->respond(['message' => 'Usuário permitido.', 'accessToken' => $jwt, 'user' => $user], 200);
        }


    }
}