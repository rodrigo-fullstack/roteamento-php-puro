<?php
namespace App\Http;

/**
 * Armazena informações relativas à requisição do cliente.
 */
class Request
{

    /**
     * Armazena a URI acessada.
     * @var string
     */
    private string $uri;

    /**
     * Armazena o método da requisição.
     * @var string
     */
    private string $method;

    /**
     * Armazena os dados da requisição (body da requisição).
     * @var string
     */
    private array $body;

    /**
     * Armazena os arquivos da requisição ($_FILES).
     * @var string
     */
    private array $files;

    /**
     * Instancia o Request com os seus devidos dados preenchidos.
     */
    public function __construct()
    {
        // Atribui a URI com a requisição do servidor.
        $this->uri = trim(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'
        );
        // Recebe o método do servidor em minúsculo.
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);

        // Define o corpo da requisição.
        $this->setBody();

        // Define os arquivos da requisição.
        $this->setFiles();
    }

    /**
     * Retorna a URI do Request.
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;

    }

    /**
     * Retorna o método usado.
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Retorna o body da requisição.
     * @return string
     */
    public function getBody(): array | null
    {
        return $this->body;
    }

    /**
     * Retorna o body da requisição.
     * @return string
     */
    public function hasFile($key): bool
    {
        return key_exists($key, $this->files);
    }

    /**
     * Retorna o arquivo desejado pelo nome (ou chave do array).
     * @return array 
     * @return null 
     */
    public function getFile($key): array | null
    {
        // Se existe o arquivo, retorna ele mesmo; se não, retorna nulo.
        return $this->files[$key] ?? null;
    }

    /**
     * Define o body da requisição.
     */
    public function setBody()
    {
        // Define com base no método da requisição.
        $this->body = json_decode(file_get_contents('php://input'), true);
    }

    /**
     * Define os arquivos da requisição.
     */
    public function setFiles()
    {
        $this->files = $_FILES ?? [];
    }
}
