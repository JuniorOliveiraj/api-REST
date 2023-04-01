<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Models\Exception;

class FavoriteNewsAdd extends Model
{
    protected $table = 'favorite_news';
    protected $primaryKey = 'id';

    protected $allowedFields = ['user_id', 'title', 'description', 'content', 'url', 'image', 'publishedAt', 'source_name', 'source_url', 'status'];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    protected $validationRules = [
        'user_id' => 'required',
        'title' => 'required',
        'description' => 'required',
        'content' => 'required',
        'url' => 'required',
        'image' => 'required',
        'publishedAt' => 'required',
        'source_name' => 'required',
        'source_url' => 'required',
        'status' => 'required',
    ];

    /**
     * Cria um novo usuário.
     *
     * @param int $user_id     O id nome do usuário.
     * @param string $title    Otitulo da noticia
     * @param string $description A descrição da noticia
     * @param string $content o comentario da noticia 
     * @param string $url A url da noticia
     * @param string $publishedAt A url da noticia
     * @param string $image A imagem da noticia
     * @param string $source_name A nome da noticia
     * @param string $source_url A url da noticia
     * @param string $status o status da noticia
     * CREATE TABLE favorite_news (
     *
     * @return int|false O ID do novo usuário, ou false se houver um erro.
     */
    public function adicionarFavoritouser($title, $description, $content, $url, $publishedAt, $image, $source_name, $source_url, $status, $user_id)
    {
        // Validar os campos obrigatórios
        if (empty($title) || empty($description) || empty($content) || empty($url) || empty($publishedAt) || empty($image) || empty($source_name) || empty($source_url) || empty($status)) {
            return false;
        }

        // Salvar o novo usuário no banco de dados
        $data = [
            'user_id' => $user_id,
            'title' => $title,
            'description' => $description,
            'content' => $content,
            'url' => $url,
            'image' => $image,
            'publishedAt' => $publishedAt,
            'source_name' => $source_name,
            'source_url' => $source_url,
            'status' => $status
        ];

        $this->insert($data);

        // Retornar o ID do novo usuário
        return $this->insertID();
    }

    public function atualizarFavoritouser($id, $title, $description, $content, $url, $publishedAt, $image, $source_name, $source_url, $status, $user_id)
    {
        // Verificar se o registro já existe

        // Atualizar o registro no banco de dados
        $data = [
            'user_id' => $user_id,
            'title' => $title,
            'description' => $description,
            'content' => $content,
            'url' => $url,
            'image' => $image,
            'publishedAt' => $publishedAt,
            'source_name' => $source_name,
            'source_url' => $source_url,
            'status' => $status
        ];
    
        $this->update($id, $data);
    
        // Retornar o ID do registro atualizado
        return $id;
    }
    


}