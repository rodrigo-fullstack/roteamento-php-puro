<?php

declare(strict_types=1);

namespace App\Controllers;

// 1. criar controller
class Controller{
    // métodos do controller para ser chamados no Core
    public function hw(){
        echo 'Hello World sem parâmetro!';
    }
    
    public function hwid($id, $email){
        echo "Hello World com parâmetro! $id $email";
    }
}