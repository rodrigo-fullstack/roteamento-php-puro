<?php

// define o router como global
global $router;

// 1 parametro é a rota, segundo é a ação inicialmente.

// no 1 parâmetro realiza a verificação através de regexpressions se está entre /
$router->get('/', function(){
    return 'Hello World!';
});

// no segundo parâmetro com a função dispatch executa o callback através do método recebido no servidor e a uri
$router->get('/sobre', function(){
     echo 'Pagina sobre!';
});


