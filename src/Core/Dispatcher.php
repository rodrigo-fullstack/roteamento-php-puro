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
     * Realiza o despache da ação necessária. Quando é uma closure (callback) que é passado, realiza a chamada da closure especificada. Quando é um controller com um método passado com @ (Controller@método), verifica se o controller existe, se o método existe, instancia uma dependência de Requisição para o método caso seja necessário e por fim executa o método.
     * @param string|callable $callback
     * @param string|array $params
     * @param string $namespace
     * @throws \Exception
     * @return mixed
     */
    public function dispatch(
        string | callable $callback,
        array $params = [],
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
        $params = $this->injectDependencies($controller, $method, $params);

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
     * @param string $controller
     * @param string $method
     * @param array $params
     */
    private function injectDependencies(string $controller, string $method, array $params){
        
        // Utiliza classe reflectionAPI para analisar propriedades e métodos da classe a ser analisada.
        $reflectionClass = new ReflectionClass($controller);
        
        // Recupera o método solicitado na requisição.
        $reflectionMethod = $reflectionClass->getMethod($method);
        
        // Recupera os parâmetros do método.
        $reflectionParameters = $reflectionMethod->getParameters();

        // Armazena as dependências na ordem em que forem detectadas.
        $dependencies = [];

        // Para cada parâmetro verifica se corresponde ao Request do Controller.
        foreach($reflectionParameters as $param){
            // Recupera classe do parâmetro. 
            // Se não for um tipo válido, retorna nulo com a ?
            $namespaceDependency = $param->getType()?->getName();
            
            // Evitar nulo para query params
            if(!$namespaceDependency){
                continue;
            }

            // Se não é instanciável, repete a estrutura de repetição.
            if(!class_exists($namespaceDependency)){ 
                continue; 
            }

            // Instancia a Dependência.
            $dependency = new $namespaceDependency();
            // Adiciona a dependência ao array de dependências.
            $dependencies[] = $dependency; 
        }
        
        // Se há dependências para ser injetadas, atribui ao array de parâmetros que pode estar incluso com parâmetros de query params.
        if(!empty($dependencies)){

            // Se somente houver dependências para ser injetadas retorna elas mesmas para ser o array de parâmetros.
            if(count($dependencies) === count($reflectionParameters)
            ){
                return $dependencies;

            } 

            // Se não, atribui ao array de parâmetros todas as dependências uma a uma na ordem em que foram encontradas. (Algoritmo de Notação Big O Quadrática).
            // TODO: Buscar solução para remover notação Big O Quadrática antiperformance.
            for($i = 0; $i < count($dependencies); $i++){
                // Atribui array com novo elemento
                $params = $this->insertInPosition($dependencies[$i], $i, $params);

                
            }
        }

        // Retorna os parâmetros.
        return $params;
    }

    /**
     * Insere na posição determinada no array. Realiza a inserção começando do fim até a posição determinada. O objetivo dessa função é ordenar as dependências do array de parâmetros para ser identificadas na instância da Controller. É feita movendo cada elemento uma casa à frente e atribuindo o elemento atual começando no fim ao elemento anterior. (Outra maneira seria criar um novo array com os elementos que não pertencem à posição e combinar em um único array.)
     * @param mixed $value
     * @param int $position
     * @param array $array
     */
    private function insertInPosition(mixed $value, int $position, array $array){
        // Itera sobre o array do início do fim ao início movendo cada elemento uma casa à frente.
        for ($i = count($array); $i > $position; $i--){
            // O atual (no caso a quantidade de elementos no início) recebe o anterior.
            $array[$i] = $array[$i-1];    
            // O anterior recebe nulo.
            $array[$i-1] = null;
        }

        // Quando chega na posição, que vai estar vazia, atribui o valor requisitado.
        $array[$position] = $value;
        return $array;
    }
}