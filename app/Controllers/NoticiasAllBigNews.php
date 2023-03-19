<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

class NoticiasAllBigNews extends Controller
{
    use ResponseTrait;

    public function index()
    {
        try {
            // Chave de API para acessar a Big News API
            $apiKey = '35575d711dd74b109840761ec0b8a97a';
            // Parâmetros da consulta de busca
            $q = 'noticias'; // Define o tema de interesse
            $lang = 'pt-br';
            $fromDate = '2023-02-13'; // Define a data de início da busca
            $sortBy = 'publishedAt'; // Define a ordenação por data de publicação
            // URL da API Big News
            // $url = "https://newsapi.org/v2/everything?q={$q}&from={$fromDate}&sortBy={$sortBy}&language={$lang}&apiKey={$apiKey}";
            $url = "https://newsapi.org/v2/everything?q={$q}&from={$fromDate}&sortBy={$sortBy}&language=pt-br&apiKey={$apiKey}";
            // Faz a requisição GET à API
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => ['User-Agent: Newsletter/1.0'] // Adiciona o header User-Agent
            ]);
            $response = curl_exec($curl);
            curl_close($curl);
            // Verifica se a resposta pode ser decodificada corretamente
            $noticias = json_decode($response);
            if (!$noticias) {
                throw new \RuntimeException('Não foi possível decodificar a resposta JSON');
            }
            // Retorna a resposta
            return $this->respond($noticias);
        } catch (\Throwable $th) {
            // Trata a exceção aqui...
            // Exibe a mensagem de erro ou registra o erro em um arquivo de log
            echo 'Erro: ' . $th->getMessage();
        }
    }
}