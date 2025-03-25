<?php

declare (strict_types = 1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Core;
use App\Core\Dispatcher;
use App\Core\RouteCollection;
use App\Http\Request;

// Instancia o router com o Core e suas Dependências.
$router = new Core(
    new RouteCollection(),
    new Dispatcher()
);

// Injeta as rotas definidas.
require __DIR__ . '/../Http/routes.php';
