<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use App\Core\Core;
use App\Core\RouteCollection;

$router = new Core(
    new RouteCollection()
);
require __DIR__ . '/../Http/routes.php';

$router->dispatch();