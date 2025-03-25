<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;

/**
 * Controladora executa ações do sistema com base na requisição do cliente e retorna uma resposta do servidor para o computador que requisita as informações.
 * 
 * A classe faz uso do conceito de injeção de dependência e query parameters
 * 
 * - Injeção de Dependência: É a capacidade de uma classe receber dependências de um meio externo sem a necessidade de criar o código nela mesma. A importância desse conceito está na redução do acoplamento e na manutenção do código
 * - Query Parameters: São parâmetros passados pela url friendly que são capturados como parãmetros de um método de uma controladora
 */
class Controller{

    /**
     * Acessado com método get sem parâmetro
     * @return void
     */
    public function getSemParam(){
        echo 'Página acessada com método get sem parâmetros.';
    }
    
    /**
     * Acessado com método get com parâmetros da URL, passados através do router que são transportados pelo Core da aplicação no método dispatch e resolve
     * @return void
     */
    public function getComParam($valor){
        echo "Página acessada com método get com parâmetros: <br>
        Dado: $valor";
    }
    
    /**
     * Acessado com método post com request através de uma injeção de dependência realizada no Dispatcher, no qual se o método possuir um parâmetro do tipo App\Http\Request e nome request realiza a instância do objeto Request para o método post.
     * @return void
     */
    public function post(Request $request){
        print_r('Página acessada com método Post e injeção de dependência na controladora de Request<br>');

        // Recupera os dados da requisição como dados de cadastro, login, dentre outros.
        print_r($request->getBody());

    }
    
    /**
     * Executa o método put com injeção de dependência da classe Request para recuperar os dados da Requisição.
     * @param \App\Http\Request $request
     * @return void
     */
    public function put(Request $request){
        print_r('Página acessada com método Put e injeção de dependência na controladora de Request<br>');
        print_r($request->getBody());
    }

    /**
     * Executa o método Delete com injeção de dependência Request 
     * @param \App\Http\Request $request
     * @return void
     */
    public function deleteComRequest(Request $request){
        print_r('Página acessada com método delete e injeção de dependência na controladora de Request<br>');
        print_r($request->getBody());

    }

    public function deleteSemRequest(){
        print_r('Página acessada com método delete sem injeção de dependência na controladora!');

    }
}