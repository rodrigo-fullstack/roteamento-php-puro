<?php
namespace App\Core;

use Exception;

class Dispatcher
{
    // recebe um controller e um método e executa sua ação
    public function dispatch(
        string | callable $callback,
        string | array $params = [],
        string $namespace = "App\\Controllers\\"
    ):  mixed {

        // se for uma closure, chama ela automaticamente
        if (is_callable($callback)) {
            return call_user_func(
                // recebe a função executando com os parâmetros indexados
                $callback,
                $params
            );
            
        }

        // controller e método recebem seus respectivos valores
        // controller recebe o elemento 0
        // método o elemento 1
        // padrão de callback: Controller@method
        [$controller, $method] = explode('@', $callback);

        // prepara namespace do controller para instanciação
        $controller = $namespace . $controller;

        // verifica se o controller existe
        if (! class_exists($controller)) {
            throw new Exception("Controller $controller não encontrado.");
        }

        $controller = new $controller;
        // se não existe o método passado na action
        if (! method_exists($controller, $method)) {
            throw new Exception("Método $method não encontrado no $controller.");
        }
        // instancia o controller e executa o método da action
        return call_user_func_array(
            // passamos o controller com o método em array
            [$controller, $method],
            // passamos os parâmetros reindexados
            array_values([$params])
        );

    }
}
