<?php

// declare (strict_types = 1);

namespace App\Core;

use Exception;

/**
 * Armazena o conjunto de rotas get, put, post ou delete e executa ações sobre elas como verificar se o uri corresponde, adicionar novas rotas, tratar URI e outros métodos.
 */
class RouteCollection
{

    /**
     * Armazena todas as rotas get.
     * @var array
     */
    public array $routesGet = [];
    
    /**
     * Armazena todas as rotas post.
     * @var array
     */
    public array $routesPost = [];
    
    /**
     * Armazena todas as rotas put.
     * @var array
     */
    public array $routesPut = [];
    
    /**
     * Armazena todas as rotas delete.
     * @var array
     */
    public array $routesDelete = [];

    /**
     * Adiciona uma rota a um dos arrays de rotas de acordo com o método, padrão de uri ou callback.
     * @param string $method
     * @param string $pattern
     * @param callable|string $callback
     * @throws \Exception
     * @return void
     */
    public function add(
        string $method, string $pattern, callable | string $callback,
    ) {

        // Define a rota.
        $route = [
            'pattern'  => $this->definePattern($pattern),
            'callback' => $callback,
        ];

        // Identifica qual o método e atribui a rota dependendo do método.
        match (strtolower($method)) {
            'get' => $this->routesGet[]       = $route,
            'post' => $this->routesPost[]     = $route,
            'put' => $this->routesPut[]       = $route,
            'delete' => $this->routesDelete[] = $route,

        // Se não existir esse método, retorna que não é suportado.
            default => throw new Exception('Método não suportado.')
        };
    }

    /**
     * Verifica se uma Uri passada corresponde às rotas armazenadas no sistema.
     * @param string $method
     * @param string $uri
     * @throws \Exception
     * @return object|null
     */
    public function match(string $method, string $uri): ?object
    {
        
        // Retorna as rotas de acordo com o método.
        $routes = match ($method) {
            'get' => $this->routesGet,
            'post' => $this->routesPost,
            'put' => $this->routesPut,
            'delete' => $this->routesDelete,
            default => throw new Exception('Método não suportado.')
        };

        // extrai a URI com análise sintática (parse)
        $parsedUri = $this->parseUri($uri);

        // para cada rota verifica se corresponde
        foreach ($routes as $route) {
            // fazer verificação da rota
            if (preg_match($route['pattern'], $parsedUri, $matches)) {
                // retorna o objeto com o padrão verificado (quer dizer, quando encontra a rota que foi determinada na uri)
                return (object) [
                    'callback' => $route['callback'],
                    'uri'      => $matches,
                ];
            }

        }
        return null;
    }

    // 5. analisa a sintaxe da uri
    private function parseUri(string $uri)
    {
        $separatedUri = $this->explodeUri($uri);
        $slicedUri    = $this->sliceUri($separatedUri);
        return $slicedUri === [] ? '' : $this->implodeUri($slicedUri);
        
        // if ($slicedUri === []) {
        //     return '';
        // }

        // $uri = $this->implodeUri($slicedUri);
        // return $uri;
    }

    // 6. define padrão da uri
    private function definePattern(string $pattern)
    {
        $pattern = implode('/',
            array_filter(
                explode('/', $pattern)
            ));

        $pattern = preg_replace(
            // transforma o {parametro} em regexpress
            '/\{[a-zA-Z_]+\}/',
            // substitui para receber qualquer valor
            '([a-zA-z0-9_]+)',

            $pattern
        );

        $pattern = $pattern !== '' || $pattern !== '/' ? $pattern : '/?';
        if($pattern === '' || $pattern === '/'){
            $pattern = '/?';
        }
        // // retorna o padrão em forma de regex
        
        return '/^' . str_replace('/', '\/', $pattern) . '$/';
    }

    private function sliceUri(array $separatedUri)
    {
        return array_slice(
            $separatedUri,
            2,
            count($separatedUri)
        );

    }

    private function explodeUri(string $uri)
    {
        // se eu colocasse a baseUrl como separator, mantém somente as /
        return explode('/', $uri);
    }

    private function implodeUri(array $separatedUri)
    {
        return implode('/', $separatedUri);
    }
}