<?php
namespace App\Http;

use Exception;

// 9. criar dispatcher para realizar direcionamento de rotas para controllers (objetivo dessa classe é reduzir a quantidade de responsabilidades do core)
class Dispatcher
{
    // recebe um controller e um método e executa sua ação
    public function dispatch(string $controller, string $action)
    {
        // namespace do controller
        $controller = 'App\\Controllers\\' . $controller;

        // se não existe o método passado na action
        if(!method_exists($controller, $action)){
            throw new Exception('Action not found');
        }
        // instancia o controller e executa o método da action
        (new $controller)->$action;

    }
}
