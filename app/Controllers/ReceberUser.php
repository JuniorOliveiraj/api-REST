<?php 
namespace App\Controllers;

use CodeIgniter\Controller;

class ReceberUser extends Controller{
    public function createUser()
    {
        $request = $this->request->getJSON();
    
        // Extrai os dados do usuário da requisição
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
    
        // Aqui você pode realizar validações e armazenar os dados do usuário em um banco de dados, por exemplo
        print_r($name);
        print_r($email);
        print_r($password);

        return $this->response->setStatusCode(201)->setJSON(['message' => 'Usuário criado com sucesso']);
    }
}