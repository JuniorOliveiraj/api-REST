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
            $apiKey = '858b8bc2fbb7c677917c9b4e16c1d1cd';//'c3d390da535fcbe6d328bb8cfcf6bfb5';

            // Parâmetros da consulta de busca
            $q = $this->request->getGet('q');
            $lang = $this->request->getGet('lang');
            $country =$this->request->getGet('country');
            $sortBy = 'relevancy';
            $max= $this->request->getGet('max');
            // URL da API GNews.io
           // $url = "https://api.gnews.io/v4/search?q={$q}&lang={$lang}&sort_by={$sortBy}&token={$apiKey}";
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