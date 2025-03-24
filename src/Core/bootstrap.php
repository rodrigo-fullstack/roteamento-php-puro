<?php

declare (strict_types = 1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\Core;
use App\Http\Request;

$router = new Core();
require __DIR__ . '/../Http/routes.php';

// testar sistema de rotas
