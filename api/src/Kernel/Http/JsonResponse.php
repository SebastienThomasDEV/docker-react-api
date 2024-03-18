<?php

namespace Api\Framework\Kernel\Http;


// Cette classe est utilisée pour envoyer une réponse JSON au client.
// Elle possède une méthode send qui permet d'envoyer une réponse JSON au client.
// dans cette méthode, on utilise la fonction http_response_code pour définir le code de statut de la réponse
// on utilise la fonction header pour définir l'en-tête de la réponse
// on utilise la fonction print pour envoyer les données au client
// on utilise la fonction json_encode pour convertir les données en JSON
use Api\Framework\Kernel\Utils\Serializer;

class JsonResponse
{
    public function __construct(private array $data = [], private int $status = 200)
    {
        $data = $this->data;
        // Need to check if any data is an object and convert it to an array
        array_walk_recursive($data, function (&$value) {
            if (is_object($value)) {
                $value = Serializer::unserialize($value);
            }
        });
        http_response_code($this->status);
        header('Access-Control-Allow-Headers: Content-Type'); // on autorise le type de contenu de la requête
//        header('Content-Type: application/json'); // on définit le type de contenu de la réponse
        header('Access-Control-Allow-Origin: *'); // on autorise les requêtes depuis n'importe quelle origine
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // on autorise les requêtes de type GET, POST, PUT, DELETE et OPTIONS
        header('Access-Control-Max-Age: 3600'); // on définit la durée de validité de la réponse en secondes
        header('Access-Control-Allow-Credentials: true'); // on autorise les requêtes avec des cookies
        print json_encode($data); // on envoie les données au client
        exit();
    }

}