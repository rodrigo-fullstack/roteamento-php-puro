<?php
namespace App\Http;

// 1. refatorar requisição

class Request
{

    private string $uri;
    private string $method;
    private array $body;
    private array $files;

    public function __construct()
    {
        $this->uri = trim(
            // remove os caracteres vazios ou / dos cantos do resultado da função parse_url
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'
        );
        // recebe o método já minúsculo
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);

        $this->setBody();
        $this->setFiles();
    }

    // retorna a uri
    public function getUri(): string
    {
        return $this->uri;

    }

    // retorna o método usado
    public function getMethod(): string
    {
        return $this->method;
    }

    // recupera o body
    public function getBody(): array | null
    {
        return $this->body;
    }

    // verifica se há arquivo
    public function hasFile($key): bool
    {
        return key_exists($key, $this->files);
    }

    // recupera arquivo com a chave
    public function getFile($key): array | null
    {
        return $this->files[$key] ?? null;
    }

    // recupera os dados (body) da requisição
    public function setBody()
    {
        $this->body = match ($this->method) {
            // recebe todos os dados de get
            'get' => $_GET,
            // recebe todos os dados de post
            'post' => $_POST,
            // recebe todos os dados de put e delete decodificando um json
            'put', 'delete' => json_decode(file_get_contents('php://input'), true) ?? [],
            default         => []
        };
    }

    // define os arquivos pelo global $_FILES
    public function setFiles()
    {
        $this->files = $_FILES ?? [];
    }
}
