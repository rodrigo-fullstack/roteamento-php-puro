<?php
declare (strict_types = 1);
namespace App\Core;

use App\Http\Request;

/**
 * Realiza o roteamento em si da aplicação. É considerado o coração da aplicação pois permite os usuários acessar os recursos do sistema.
 */
class Core
{

    /**
     * Instancia o roteamento passando como parâmetro o RouteCollection e o Dispatcher de maneira manual.
     * @param \App\Core\RouteCollection $routes
     * @param \App\Core\Dispatcher $dispatcher
     */
    public function __construct(
        private RouteCollection $routes,
        private Dispatcher $dispatcher,
    ) {}

    /**
     * Adiciona uma rota get com um padrão e um callback que pode ser um controlador com seu método (Controladora@método) ou closure (função anônima).
     * @param string $pattern
     * @param callable|string $callback
     * @return void
     */
    public function get(string $pattern, callable | string $callback): void
    {
        $this->routes->add('get', $pattern, $callback);
    }

    /**
     * Adiciona uma rota post com um padrão e um callback que pode ser um controlador com seu método (Controladora@método) ou closure (função anônima).
     * @param string $pattern
     * @param callable|string $callback
     * @return void
     */
    public function post(string $pattern, callable | string $callback): void
    {
        $this->routes->add('POST', $pattern, $callback);

    }

    /**
     * Adiciona uma rota put com um padrão e um callback que pode ser um controlador com seu método (Controladora@método) ou closure (função anônima).
     * @param string $pattern
     * @param callable|string $callback
     * @return void
     */
    public function put(string $pattern, callable | string $callback): void
    {
        $this->routes->add('PUT', $pattern, $callback);

    }

    /**
     * Adiciona uma rota delete com um padrão e um callback que pode ser um controlador com seu método (Controladora@método) ou closure (função anônima).
     * @param string $pattern
     * @param callable|string $callback
     * @return void
     */
    public function delete(string $pattern, callable | string $callback): void
    {
        $this->routes->add('DELETE', $pattern, $callback);
    }

    /**
     * Método executado quando a rota não existe, retornando um erro 404 not found.
     * @return void
     */
    public function notFound(): void
    {
        header("HTTP/1.0 404 Not Found");
        echo '404 Not Found';
    }

    /**
     * Analisa se é uma rota válida para ser despachada. Passa a requisição com injeção de dependência.
     * @param \App\Http\Request $request
     * @return void
     */
    public function resolve(
        Request $request
    ): void {
        // Recupera a rota correspondente ao URI; caso não esteja correspondentem, retorna um nulo.
        $route = $this->routes->match(
            $request->getMethod(), $request->getUri()
        );

        print_r(!$route ? 'Nulo' : 'Preenchido');

        // Se não encontrar essa rota, retorna um erro 404 not found; se encontrar vai realizar o despache da rota.
        $route === null ?
        (function () {
            $this->notFound();
        })() :
        $this->dispatch($route);

    }

    /**
     * Realiza o despache das rotas pelo objeto Dispatch.
     * @param object $route
     * @param string $namespace
     * @return mixed
     */
    public function dispatch(object $route, string $namespace = "App\\Controllers\\"): mixed
    {
        // Retorna o dispatcher despachando a rota com o callback e a uri.
        return $this->dispatcher->dispatch(
            callback: $route->callback,
            params: $this->parseParams($route->uri),
            namespace :$namespace
        );

    }

    /**
     * Realiza o tratamento dos parâmetros com base na uri fornecida vinda da função preg_match da regular expression de RouterCollection->match().
     * @param mixed $uri
     */
    private function parseParams($uri)
    {

        return array_slice($uri, 1);

    }
}
