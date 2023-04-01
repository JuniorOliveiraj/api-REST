<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Models\FavoriteNewsAdd;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
class NoticiaFavorita extends ResourceController
{
    public function adicionar()
    {
        // Verificar se a requisição é do tipo OPTIONS
        // Obter as credenciais do usuário do corpo da solicitação POST
        $id = $this->request->getVar('id');

        $noticia = $this->request->getVar('noticia');
        $status = $this->request->getVar('status');

        $response = [
            'user_id' => $id,
            'title' => $noticia['title'],
            'description' => $noticia['description'],
            'content' => $noticia['content'],
            'url' => $noticia['url'],
            'image' => $noticia['image'],
            'publishedAt' => $noticia['publishedAt'],
            'source_name' => $noticia['source']['name'],
            'source_url' => $noticia['source']['url'],
            'status' => $status
        ];


        $userModel = new UserModel();
        $FavoriteNewsAdd = new FavoriteNewsAdd();
        $user = $userModel->where('id', $id)->first();
        $noticiasEzistentes = $FavoriteNewsAdd->where('user_id', $id)->where('title', $noticia['title'])->first();
        if (!$user) {
            return $this->respond(['erro' => 'usuario não autorizado '], 200);
        }
        //caso ja esiver noticias ele apenas muda o status 
        if ($noticiasEzistentes) {
            $noticiaFavorita = $FavoriteNewsAdd->where('user_id', $id)->where('title', $noticia['title'])->first();
            $noticiaFavorita['status'] = $status;
            $FavoriteNewsAdd->update($noticiaFavorita['id'], $noticiaFavorita);
            return $this->respond(['valor status' => $noticiaFavorita['status']], 200);
        }
        if (!$noticiasEzistentes) {
            $FavoriteNewsAdd->insert($response);
        }


        return $this->respond('adicionado com sucesso', 200);



    }


    public function listarFavoritas()
    {
        $id = $this->request->getVar('id');
        $userModel = new UserModel();
        $user = $userModel->where('id', $id)->first();
        if (!$user) {
            return $this->respond(['erro' => 'usuario não autorizado'], 200);
        }
        $FavoriteNewsAdd = new FavoriteNewsAdd();


        $noticiasEzistentes = $FavoriteNewsAdd->where('user_id', $id) ->where('status', 0)->findAll();

        $noticias = [];

        foreach ($noticiasEzistentes as $noticia) {
            $noticias[] = [
                'status'=>$noticia['status'],
                'title' => $noticia['title'],
                'content' => $noticia['content'],
                'description' => $noticia['description'],
                'image' => $noticia['image'],
                'publishedAt' => $noticia['publishedAt'],
                'url' => $noticia['url'],
                'source' => [
                    'name' => $noticia['source_name'],
                    'url' => $noticia['source_url']
                ]
            ];
        }
        return $this->respond($noticias, 200);
    }

}