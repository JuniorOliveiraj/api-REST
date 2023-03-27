<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class UsersAllController extends Controller
{
    protected $middleware = ['jwt'];

    public function index()
    {
        $model = new UserModel();
        $users = $model->findAll();
        return $this->response->setJSON($users);
    }
}