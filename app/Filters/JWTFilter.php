<?php

namespace App\Filters;

use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;

class JWTFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Obter o token JWT da solicitação
        $token = $request->getHeaderLine('Authorization');
        if (!$token) {
            return Services::response()->setStatusCode(401)->setJSON(['error' => 'Token de autenticação não fornecido.']);
        }

        // Verificar e decodificar o token JWT
// Verificar e decodificar o token JWT
        try {
            $key = "$2y$10MFKDgDBujKwY.VZi/DH6JuR58ISGjlS6mlEobHlmhX9zQ.Ha4c3qC2"; // Chave secreta para gerar tokens JWT
            
            var_dump( $token);
            var_dump( $token);
           
            $decoded = JWT::decode($token, $key, 'HS256');

            $userId = $decoded->user_id; // Obtém o ID do usuário do token decodificado
        } catch (\Exception $e) {
            return Services::response()->setStatusCode(401)->setJSON(['error' => 'Token de autenticação inválido. :(']);
        }


        // Verificar se o usuário possui permissão para acessar o recurso
        $permission = $request->getMethod() . ':' . $request->uri->getPath(); // Obtém o método HTTP e o caminho da URI da solicitação
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        if (!$user || !$user->hasPermission($permission)) {
            return Services::response()->setStatusCode(403)->setJSON(['error' => 'Usuário não possui permissão para acessar este recurso.']);
        }

        return;
    }


    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {


        // Nada a fazer aqui
    }
}