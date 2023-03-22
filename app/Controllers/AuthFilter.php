<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
class AuthFilter implements FilterInterface
{
    // Chave secreta para verificar tokens JWT
    private $key = "sua_chave_secreta_aqui";

    public function before(RequestInterface $request, $arguments = null)
    {
        // Obter o token de autenticação do cabeçalho "Authorization"
        $authHeader = $request->getServer('HTTP_AUTHORIZATION');
        if (!$authHeader) {
            return redirect()->to('/login');
        }
        $token = str_replace('Bearer ', '', $authHeader);

        // Verificar se o token é válido usando a biblioteca JWT
        try {
            $decoded = JWT::decode($token, $this->key, array('HS256'));
        } catch (\Exception $e) {
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Não faz nada depois da requisição
    }
}
