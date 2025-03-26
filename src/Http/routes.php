<?php

// Define o router como global
global $router;

/**
 * Roteamento através do router:
 * $router->metodo('/rota', 'Controladora@Método');
 * $router->metodo('/rota', function(){
 * 
 * });
 */

/**
 * Acessa somente com método get sem query params (pela url do navegador).
 */
$router->get('/get-sem-param', 'Controller@getSemParam');

/**
 * Acessa somente com método get com query params (pela url do navegador).
 */
$router->get('/get-com-param/{valor}', 'Controller@getComParam');

/**
 * Acessa somente com método post sem query params (pela submissão de formulário).
 */
$router->post('/post', 'Controller@post');

/**
 * Acessa somente com método post sem query params (pela submissão de formulário).
 */
$router->post('post-com-param/{id}', 'Controller@postQueryParams');

$router->post('post-com-param-service/{id}', 'Controller@postDependenciesQueryParams');

/**
 * Acessa somente com método post sem query params (pela submissão de formulário).
 */
$router->put('/put', 'Controller@put');

/**
 * Acessa somente com método delete com request (pela submissão de formulário ou solicitação assíncrona).
 */
$router->delete('/delete-com-request', 'Controller@deleteComRequest');

/**
 * Acessa somente com método delete sem request (pela submissão de formulário ou solicitação assíncrona).
 */
$router->delete('/delete-sem-request', 'Controller@deleteSemRequest');

