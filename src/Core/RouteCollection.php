<?php

// declare (strict_types = 1);

namespace App\Core;

use Exception;

// 2. criar conjuntos de rotas
class RouteCollection
{
    public array $routesGet    = [];
    // users GET
    public array $routesPost   = [];
    // users/store POST
    public array $routesPut    = [];
    // users/update PUT
    public array $routesDelete = [];
    // users/delete DELETE

    // 3. adicionar rota a conjunto de rotas de acordo com o método passado
    public function add(
        string $method, string $pattern, callable | string $callback,
    ) {
        // define a rota
        $route = [
            // com um padrão
            'pattern'  => $this->definePattern($pattern),
            // e um método
            'callback' => $callback,
        ];

        match (strtolower($method)) {
            // atribui a rota dependendo do método
            'get' => $this->routesGet[]       = $route,
            'post' => $this->routesPost[]     = $route,
            'put' => $this->routesPut[]       = $route,
            'delete' => $this->routesDelete[] = $route,

        // se não existir esse método, retorna que não é suportado.
            default => throw new Exception('Método não suportado.')
        };
    }

    // 4. verifica a rota correspondente à uri
    public function match(string $method, string $uri): ?object
    {
        // retorna as rotas de acordo com o método

        $routes = match ($method) {
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

        echo '<pre>';
        print_r($parsedUri);


        
        // para cada rota verifica se corresponde
        foreach ($routes as $route) {
            // fazer verificação da rota
            if ($route['pattern'] === $parsedUri) {
                return (object) [
                    'callback' => $route['callback'],
                    'uri'      => $route['pattern'],
                ];
            }
            echo '<pre>';
            print_r($this->hasQueryParams($parsedUri, $route['pattern']));    

            // verificar se possui query parameters
            // preciso verificar se em cada parte do padrão existe uma string que inicia com { e termina com } e possui alguns caracter no meio
            

            
            // não consegue identificar o padrão em regex
            // preg_match()
            // if (preg_match($route['pattern'], $parsedUri, $matches)) {

            //     // retorna o objeto com o padrão verificado (quer dizer, quando encontra a rota que foi determinada na uri)
            //     return (object) [
            //         'callback' => $route['callback'],
            //         'uri'      => $matches,
            //     ];
            // }
        }

        // foreach($routes as $route['pattern']){

        // }


        die();
        return null;
    }

    // 5. analisa a sintaxe da uri
    private function parseUri(string $uri)
    {
        $separatedUri = $this->explodeUri($uri);
        $slicedUri    = $this->sliceUri($separatedUri);
        // echo '<pre> Exibindo uri    ';
        // print_r($slicedUri === '' ? 'Vazia' : 'Preenchido');
        if ($slicedUri === '') {
            return $slicedUri;
        }

        $uri = $this->implodeUri($slicedUri);
        return $uri;
        // provavelmente serve para consertar a URI que vem com contra barra
        // echo '<pre>';
        // print_r(implode('/', array_filter(
        //     explode('/', $uri)
        // )));
        // return implode('/',
        //     array_filter(
        //         explode('/', $uri)
        //     ));
    }

    // 6. define padrão da uri
    private function definePattern(string $pattern)
    {
        $pattern = implode('/',
            array_filter(
                explode('/', $pattern)
            ));
        // echo '<pre>';
        // print_r($pattern);
        // echo '<br>';
        return $pattern;

        // $pattern = preg_replace(
        //     // transforma o {parametro} em regexpress
        //     '/\{[a-zA-Z_]+\}/',
        //     '([a-zA-z0-9_]+)',
        //     $pattern
        // );
        // // retorna o padrão em forma de regex
        // return '/^' . str_replace('/', '\/', $pattern) . '$/';

    }

    private function sliceUri(array $separatedUri)
    {
        $slicedUri = array_slice(
            $separatedUri,
            2,
            count($separatedUri)
        );
        if ($slicedUri === []) {
            return '';
        }

        return $slicedUri;
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

    private function hasQueryParams(mixed $uri, string $pattern){
        
        
        $uri = explode('/', $uri);
        $pattern = explode('/', $pattern);
        echo '<pre>';
        for($i = 0; $i < count($pattern); $i++){
            $strlen = mb_strlen($uri);
            if($this->hasValidQueryParam([
                'strlen' => $strlen,
                'startPartUrl' => $partUrl[0],
                'endPartUrl' => $partUrl[$strlen - 1]
            ])){
                return true;
            }

        }

        print_r($pattern);
        echo '<pre>';
        print_r($uri);

        // die();

        
    }

    private function hasValidQueryParam(array $data){
        return $data['strlen'] !== 2 
        && $data['startPartUrl'][0] === '{' 
        && $data['endPartUrl'] === '}';
    }
}
