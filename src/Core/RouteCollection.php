<?php

declare (strict_types = 1);

namespace App\Core;

use Exception;

// 2. criar conjuntos de rotas
class RouteCollection
{
    public array $routesGet    = [];
    public array $routesPost   = [];
    public array $routesPut    = [];
    public array $routesDelete = [];

    // 3. adicionar rota a conjunto de rotas de acordo com o método passado
    public function add(
        string $method, string $pattern, callable|string $callback, 
    ){
        // define a rota
        $route = [
            // com um padrão 
            'pattern' => $this->definePattern($pattern), 
            // e um método 
            'callback' => $callback
        ];

        match(strtolower($method)){
            // atribui a rota dependendo do método
            'get' => $this->routesGet[] = $route,
            'post' => $this->routesPost[] = $route,
            'put' => $this->routesPut[] = $route,
            'delete' => $this->routesDelete[] = $route,

            // se não existir esse método, retorna que não é suportado.
            default => throw new Exception('Método não suportado.')
        };
    }

    // 4. verifica a rota correspondente à uri
    public function match(string $method, string $uri): ?object
    {
        // retorna as rotas de acordo com o método
        $routes = match(strtolower($method)){
            'get' => $this->routesGet,
            'post' => $this->routesPost,
            'put' => $this->routesPut,
            'delete' => $this->routesDelete,
            default => throw new Exception('Método não suportado.')
        };

        // echo '<pre>';
        // print_r($routes);
        
        // extrai a URI com análise sintática (parse)
        $parsedUri = $this->parseUri($uri);

        // echo '<pre>';
        // print_r($parsedUri);

        // para cada rota verifica se corresponde 
        foreach($routes as $route){
            // echo '<pre>';
            // print_r(preg_match($route['pattern'], $parsedUri, $matches));
            
            if(preg_match($route['pattern'], $parsedUri, $matches)){
                // retorna o objeto com o padrão verificado (quer dizer, quando encontra a rota que foi determinada na uri)
                return (object) [
                    'callback' => $route['callback'],
                    'uri' => $matches
                ];
            }
        }

        return null;
    }

    // 5. analisa a sintaxe da uri
    private function parseUri(string $uri){
        // provavelmente serve para consertar a URI que vem com contra barra
        // echo '<pre>';
        // print_r(implode('/', array_filter(
        //     explode('/', $uri)
        // )));
        return implode('/', 
                array_filter(
                    explode('/', $uri)
                ));
    }

    // 6. define padrão da uri
    private function definePattern(string $pattern){
        $pattern = implode('/', 
        array_filter(
            explode('/', $pattern)
        ));
        $pattern = preg_replace(
        // transforma o {parametro} em regexpress
            '/\{[a-zA-Z_]+\}/',
            '([a-zA-z0-9_]+)',
            $pattern        
        );
        // retorna o padrão em forma de regex
        return '/^' . str_replace('/', '\/', $pattern) . '$/';
    }
}
