<?php

// define o router como global
global $router;

/**
 * Roteamento através do router:
 * $router->metodo('/rota', 'Controladora@Método');
 * $router->metodo('/rota', function(){
 * 
 * });
 */

/**
 * Acessa somente com método get sem query params
 */
$router->get('/get-sem-param', 'Controller@getSemParam');

/**
 * Acessa somente com método get com query params
 */
$router->get('/get-com-param/{valor}', 'Controller@getComParam');

/**
 * Acessa somente com método post sem query params
 */
$router->post('/post', 'Controller@post');

/**
 * Acessa somente com método delete com request
 */
$router->delete('/delete-com-request', 'Controller@deleteComRequest');

/**
 * Acessa somente com método delete sem request
 */
$router->delete('/delete-sem-request', 'Controller@deleteSemRequest');

