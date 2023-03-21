<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\APi\ResponseTrait;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

class NoticiasAll extends Controller
{
    use ResponseTrait;

    public function buscarNoticias()
    {
        try {
            // Chave de API para acessar a GNews.io
            $apiKey ='8fef4b47ef03be9ddfbf8ffd7c6793ca'; //'858b8bc2fbb7c677917c9b4e16c1d1cd';//'c3d390da535fcbe6d328bb8cfcf6bfb5';  

            // Parâmetros da consulta de busca
            $q = $this->request->getGet('q');
            $lang = $this->request->getGet('lang');
            $country = $this->request->getGet('country');
            $sortBy = 'relevancy';
            $max = $this->request->getGet('max');

            // URL da API GNews.io
            $url = "https://gnews.io/api/v4/search?q={$q}&lang={$lang}&country={$country}&max={$max}&apikey={$apiKey}";

            // Faz a requisição GET à API
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $url,
            ]);
            $response = curl_exec($curl);
            curl_close($curl);

            // Verifica se a resposta pode ser decodificada corretamente
            $noticias = json_decode($response, true);
            if (!$noticias) {
              //  throw new \RuntimeException('Não foi possível decodificar a resposta JSON');
              return $this->respond(['message' => 'Limite de requisições diárias excedido']);   
            }

            // Verifica se a resposta contém uma mensagem de erro de limite excedido
            if (isset($noticias['errors']) && $noticias['errors'][0] === 'You have reached your request limit for today, the next reset will be tomorrow at midnight UTC. If you need more requests, you can upgrade your subscription here: https://gnews.io/#pricing') {
                return $this->respond(['message' => 'Limite de requisições diárias excedido']);
            }
          
            // Limita o número de notícias a um máximo de 100
            $maxNoticias = min(count($noticias['articles']), 100);

            // Seleciona somente as primeiras 100 notícias
            $noticiasLimitadas = array_slice($noticias['articles'], 0, $maxNoticias);

            // Cria um novo array associativo apenas com as notícias limitadas
            $noticias = array('totalArticles' => $noticias['totalArticles'], 'articles' => $noticiasLimitadas);

            // Retorna a resposta
            return $this->respond($noticias);
        } catch (\Throwable $th) {
            // Trata a exceção aqui...
            // Exibe a mensagem de erro ou registra o erro em um arquivo de log
            echo 'Erro: ' . $th->getMessage();
        }
    }
}
