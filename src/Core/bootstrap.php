<?php

declare (strict_types = 1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Core;
use App\Core\Dispatcher;
use App\Core\RouteCollection;
use App\Http\Request;

$router = new Core(
    new RouteCollection(),
    new Dispatcher()
);
require __DIR__ . '/../Http/routes.php';

// testar sistema de rotas
