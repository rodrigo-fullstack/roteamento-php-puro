<?php 

declare(strict_types=1);
use App\Http\Request;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../src/Core/bootstrap.php';

$request = new Request();

// echo '<pre>';
// print_r($request);
$router->resolve($request);