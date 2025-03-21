<?php

namespace App\Http;

// 7. criar a classe de requisição
class Request{

    // retorna a uri
    public static function uri(){
        return trim(
            // remove os caracteres vazios ou / dos cantos do resultado da função parse_url
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'
        );

    }

    // retorna o método usado 
    public static function method(){
        return $_SERVER['REQUEST_METHOD'];
    }

}