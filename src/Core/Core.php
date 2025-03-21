<?php
namespace App\Core;

use App\Http\Request;
use Exception;

// 8. criar classe do core que direciona as rotas para seus devidos controllers
class Core
{

    public function __construct(
        private RouteCollection $routes = new RouteCollection()
    ) {}

    // adiciona as rotas a cada um dos arrays de rotas

    public function get(string $pattern, callable|string $callback): void
    {
        $this->routes->add('GET', $pattern, $callback);
    }
    
    public function post(string $pattern, callable|string $callback): void
    {
        $this->routes->add('POST', $pattern, $callback);

    }

    public function put(string $pattern, callable|string $callback): void
    {
        $this->routes->add('PUT', $pattern, $callback);

    }

    public function delete(string $pattern, callable|string $callback): void
    {
        $this->routes->add('DELETE', $pattern, $callback);
    }

    // realiza o direcionamento das rotas
    public function dispatch(): void
    {
        // inicialmente está funcionando somente com closures

        
        // verificar se o método e a rota corresponde 
        $callback = $this->routes->match(Request::method(), Request::uri());
        
        // se não encontrou a rota correspondente
        if(!$callback){
            throw new Exception('404 - not found');
            // return $callback;
        }

        // executa a closura
        call_user_func($callback);
    }

}
