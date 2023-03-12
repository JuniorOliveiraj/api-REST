<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UsersAll;
class UsersAllController extends Controller{
    public function index ()
    {
        // criar uma instância do modelo UsuariosAll
        $model = new UsersAll();

        // buscar todos os usuários do banco de dados
        $usuarios = $model->findAll();

        // retornar a lista de usuários em formato JSON
        return $this->response->setJSON($usuarios);
    }
}

