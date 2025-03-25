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

        // Extrai a URI separando a parte desnecessária.
        $parsedUri = $this->parseUri($uri);

        // Para cada rota verifica se corresponde ao padrão pela função preg_match.
        foreach ($routes as $route) {

            // Se o padrão está presente na uri então vai retornar um objeto da rota a partir do que foi identificado em matches.
            if (preg_match($route['pattern'], $parsedUri, $matches)) {
                
                return (object) [
                    'callback' => $route['callback'],
                    'uri'      => $matches,
                ];
            }

        }

        // Se não corresponder retornará um nulo.
        return null;
    }

    /**
     * Separa a parte da URI necessária para ser verificada.
     * @param string $uri
     * @return string
     */
    private function parseUri(string $uri)
    {
        // Separa a URI por barras.
        $separatedUri = $this->explodeUri($uri);
        
        // Despreza o início da URI "roteamento-php-vanilla/public" sobrando somente o que vem depois de public.
        $slicedUri = $this->sliceUri($separatedUri);

        // Retorna vazio se o resultado do desprezo acima foi um array vazio; Se não, retorna a URI unida com / novamente.
        return $slicedUri === [] ? '' : $this->implodeUri($slicedUri);
    }

     /**
     * Separa a URI pelas /.
     * @param string $uri
     * @return bool|string[]
     */
    private function explodeUri(string $uri)
    {
        return explode('/', $uri);
    }

    /**
     * Despreza a parte do uri relativo aos diretórios da aplicação: roteamento-php-vanilla/public.
     * @param array $separatedUri
     * @return array
     */
    private function sliceUri(array $separatedUri)
    {
        return array_slice(
            $separatedUri,
            2,
            count($separatedUri)
        );

    }

    /**
     * Realiza a junção da URI modificada pelas /
     * @param array $separatedUri
     * @return string
     */
    private function implodeUri(array $separatedUri)
    {
        return implode('/', $separatedUri);
    }

    /**
     * Define padrão da URI para ser armazenado nos arrays de rotas.
     * @param string $pattern
     * @return string
     */
    private function definePattern(string $pattern)
    {
        // Filtra o padrão separando com / e juntando novamente.
        $pattern = implode('/',
            array_filter(
                explode('/', $pattern)
            ));

        // Substitui no lugar de {Alguma_coisa} a expressão regular correspondente para receber qualquer dado.
        $pattern = preg_replace(
            '/\{[a-zA-Z_]+\}/',
            '([a-zA-z0-9_]+)',
            $pattern
        );

        // Se o resultado dessa substituição foi um vazio ou somente uma /, coloca que o padrão é uma /? (/ opcional).
        if ($pattern === '' || $pattern === '/') {
            $pattern = '/?';
        }
        
        // Retorna o padrão em forma de regex '/^' início da string, str_replace('/', '\/', $pattern) padrão da string, '$/';.
        return '/^' . str_replace('/', '\/', $pattern) . '$/';
    }
}
