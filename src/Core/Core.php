<?php
declare (strict_types = 1);
namespace App\Core;

use App\Http\Request;

class Core
{

    public function __construct(
        private RouteCollection $routes = new RouteCollection(),
        private Dispatcher $dispatcher = new Dispatcher()
    ) { }

    // adiciona as rotas a cada um dos arrays de rotas

    public function get(string $pattern, callable | string $callback): void
    {
        $this->routes->add('GET', $pattern, $callback);
    }

    public function post(string $pattern, callable | string $callback): void
    {
        $this->routes->add('POST', $pattern, $callback);

    }

    public function notFound(): void
    {
        header("HTTP/1.0 404 Not Found");
        echo '404 Not Found';
    }

    // public function put(string $pattern, callable | string $callback): void
    // {
    //     $this->routes->add('PUT', $pattern, $callback);

    // }

    // public function delete(string $pattern, callable | string $callback): void
    // {
    //     $this->routes->add('DELETE', $pattern, $callback);
    // }

    // realiza o roteamento
    public function resolve(
        // realiza injeção de dependência quando vai realizar o roteamento
        Request $request
    ): void {
        // echo '<pre> Dentro do Resolve';
        // print_r($request);
        // recupera a rota
        $route = $this->routes->match(
            $request->getMethod(), $request->getUri()
        );

        // se não encontrar a rota retorna um not found
        if ($route === null) {
            $this->notFound();
            return;
        }

        $this->dispatch($route);

    }

    // realiza o direcionamento das rotas
    public function dispatch(object $route, string $namespace = "App\\Controllers\\"): mixed
    {
        // retorna o dispatcher despachando a rota com o callback e a uri
        return $this->dispatcher->dispatch(
            callback: $route->callback,
            params: $route->uri,
            namespace: $namespace
        );
    }

}
