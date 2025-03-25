<?php
declare (strict_types = 1);

namespace App\Core;

use App\Http\Request;
use Exception;
use ReflectionClass;
use ReflectionParameter;

/**
 * Realiza o despache das rotas instanciando os controllers, executando seus métodos e injetando dependência nos métodos necessários.
 */
class Dispatcher
{
    
    /**
     * Realiza o despache da ação necessária. Quando é uma closure (callback) que é passado, realiza a chamada da closure especificada. Quando é um controller com um método passado com @ (Controller@método), verifica se o controller existe, se o método existe.
     * @param string|callable $callback
     * @param string|array $params
     * @param string $namespace
     * @throws \Exception
     * @return mixed
     */
    public function dispatch(
        string | callable $callback,
        string | array $params = [],
        string $namespace = "App\\Controllers\\"
    ):  mixed {

        // Se for uma closure, chama ela automaticamente.
        if (is_callable($callback)) {
            return call_user_func(
                $callback,
                array_values($params)
            );
            
        }

        // Controller e Método recebem seus respectivos valores com a separação pelo @.
        [$controller, $method] = explode('@', $callback);
        
        // Prepara o controller para instância pelo namespace.
        $controller = $namespace . $controller;

        // Verifica se o controller existe.
        if (! class_exists($controller)) {
            throw new Exception("Controller $controller não encontrado.");
        }
        
        // Verifica se o método da controladora existe.
        if (! method_exists($controller, $method)) {
            throw new Exception("Método $method não encontrado no $controller.");
        }

        // Realiza injeção de dependência do Request nos parâmetros.
        $params = $this->injectDependency($controller, $method, $params);

        // Instancia a Controladora.
        $controller = new $controller;

        // Executa o método da controladora com os parâmetros recebidos.
        return call_user_func_array(
            [$controller, $method],
            array_values($params)
        );

    }

    /**
     * Injeta dependência via método na Controladora caso haja Request como Parâmetro.
     * @param mixed $controller
     * @param mixed $method
     * @param mixed $params
     */
    public function injectDependency($controller, $method, $params){
        // Utiliza classe reflectionAPI para analisar propriedades e métodos da classe a ser analisada.
        $reflectionClass = new ReflectionClass($controller);
        
        // Recupera o método solicitado na requisição.
        $reflectionMethod = $reflectionClass->getMethod($method);
        
        // Recupera os parâmetros do método.
        $reflectionParameters = $reflectionMethod->getParameters();

        // Para cada parâmetro verifica se corresponde ao Request do Controller.
        foreach($reflectionParameters as $param){
            // TODO: Realizar verificação para todo tipo de classe injetada, não somente o controller..
            
            // Verifica se o parâmetro possui o nome request e é do tipo Request.
            if($param->getName() === 'request' && 
                $param->getType()->getName() === 'App\\Http\\Request'){

                // Instancia o Request.
                $request = new Request(); 
                break;
            }
        }

        // Se está definido o request como parâmetro.
        if(isset($request)){

            // Se os parâmetros for iguais a 1 atribui um único parâmetro como sendo o próprio request.
            if(count($params) === 1){
                $params[0] = $request;

                // Se não, deixa o Request como sendo o primeiro como parâmetro de seu controlador.
            } else{
                for ($i = count($params); $i > 0; $i++){
                    $params[$i+1] = $params[$i];    
                    $params[$i] = null;
                }
                $params[0] = $request;
            }
        }

        // Retorna os parâmetros.
        return $params;
    }
}
