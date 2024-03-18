<?php

namespace Api\Framework\Kernel\Services;

use Api\Framework\Kernel\Abstract\AbstractService;
use Api\Framework\Kernel\Utils\Utils;

class Request extends AbstractService
{

    private array $get;
    private array $post;

    private array $headers;

    private string $bearerToken;

    public function __construct()
    {

        $this->get = $_GET;
        $this->post = Utils::getRequestBody();
        $this->headers = getallheaders();
        $token = $this->headers['Authorization'] ?? null;
        if ($token) {
            $this->bearerToken = trim(str_replace('Bearer ', '', $token));
        } else {
            $this->bearerToken = '';
        }
        parent::__construct(get_class($this));
    }

    /**
     * Cette méthode permet de récupérer les paramètres GET de la requête
     * Utilise la superglobale $_GET
     *
     * @return array
     */
    public final function retrieveGetValues(): array
    {
        return $this->get;
    }

    /**
     * Cette méthode permet de récupérer les paramètres POST de la requête
     * Utilise la lecture du corps de la requête HTTP
     * @return array
     */
    public final function retrievePostValues(): array
    {
        return $this->post;
    }

    /**
     * Cette méthode permet de récupérer un paramètre GET de la requête selon sa clé
     * Utilise la superglobale $_GET
     * @param string $key
     * @return mixed
     */
    public final function retrieveGetValue(string $key): mixed
    {
        return $this->get[$key];
    }

    /**
     * Cette méthode permet de récupérer un paramètre POST de la requête selon sa clé
     * Utilise la lecture du corps de la requête HTTP
     * @param string $key
     * @return mixed
     */
    public final function retrievePostValue(string $key): mixed
    {
        return $this->post[$key];
    }

    /**
     * Cette méthode permet de récupérer les headers de la requête
     * Utilise la fonction getallheaders()
     * @return array
     */
    public final function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Cette méthode permet de récupérer le token JWT de la requête
     * Elle lit le header Authorization de la requête et retourne la valeur du token JWT
     * @return string
     */
    public final function getBearerToken(): string
    {
        return $this->bearerToken;
    }



}