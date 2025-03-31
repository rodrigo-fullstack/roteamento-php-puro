# Projeto Sistema de Roteamento Aprendizado

Projeto de um sistema de roteamento criado com base no blog site do Desenvolvedor Alexandre. Esse projeto serviu de aprendizado para compreender os conceitos envolvidos na criação de um sistema de roteamento para ser implementado em sistemas posteriores em PHP puro.

Fonte do Projeto (feito até a parte 2): 
* [Parte 1](https://paginadoale.com.br/2024/09/sistema-de-rotas-mvc-em-php-8-parte-1/)
* [Parte 2](https://paginadoale.com.br/2024/09/sistema-de-rotas-mvc-em-php-8-parte-2/)


## Como testar a aplicação

1. Baixe a pasta do projeto.
2. Depois renomeie o nome da pasta para roteamento-php-vanilla caso não esteja dessa maneira.
3. Em seguida, cole essa pasta no seu localhost (htdocs no xampp ou html no apache)
4. Abra seu navegador e digite o endereço: `http://localhost/roteamento-php-puro/public`
5. Acesse uris pelo navegador como: 

* /get-sem-param
* /get-com-param/{algum-valor}

6. Acesse uris pelo seu testador de API como Insomnia ou Postman:

* /post com método POST e body de requisição json aleatório
* /delete-com-request com método DELETE com body de requisição json aleatório
* /delete-sem-request com método DELETE sem body de requisição 
* /put com método PUT com body de requisição json aleatório

## Conceitos Implementados 

### PHP (Linguagem e Recursos Fundamentais)

* Variáveis e Tipagem
* Arrays e Estruturas de Dados
* Funções e Closures
* Execução de Closures e Métodos Dinâmicos

### Programação Orientada a Objetos (OOP) em PHP

* Classes e Objetos
* Propriedades e Métodos
* Instanciação Dinâmica de Classes
* Reflection API

### Backend e Desenvolvimento Web

* Configuração de Servidor
* Manipulação de Requisições HTTP
* Roteamento e URL Amigáveis

### Arquitetura de Software

* Padrão MVC (Model-View-Controller)
* Injeção de Dependência e Gerenciamento de Serviços


