<?php 

declare(strict_types=1);
use App\Http\Request;

/**
 * Exibição de erros no navegador (não recomendado para ambientes de produção).
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


/**
 * Realiza a chamada do Router.
 */
require __DIR__ . '/../src/Core/bootstrap.php';


// TODO: Investigar como fazer para injetar dependência com a instância do core sem injetar manualmente.
$request = new Request();
// Passa a requisição da URI e o método pelo objeto Request.
$router->resolve($request);