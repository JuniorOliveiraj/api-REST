<?php 
namespace App\Models;

use CodeIgniter\Model;

class UsersAll extends Model{
    protected $table      = 'sis_usuario';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $tabe = 'sis_usuario';
    protected  $priaryKye = 'id';
    protected  $allOwedFields = ['ds_nome', 'ds_email', 'ds_telefone'];
    protected $validation =[
        'ds_nome' => 'required|min_length[3]|is_unique[sis_usuario.ds_nome]'
    ];
}

