<?php

// define o router como global
global $router;

// 1 parametro é a rota, segundo é a ação inicialmente.

// no 1 parâmetro realiza a verificação através de regexpressions se está entre /
$router->get('/', fn() => print 'Hello World');

// no segundo parâmetro com a função dispatch executa o callback através do método recebido no servidor e a uri
$router->get('sobre', fn() => print 'Pagina sobre!');
$router->get('/users/store', fn() => print 'Pagina sobre!');
$router->get('helloWorld/users/{id}/{email}', 'Controller@hwid');
$router->get('/helloWorld', 'Controller@hw');
