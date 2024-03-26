<?php

namespace Api\Framework\Kernel\Abstract;
use Api\Framework\Kernel\Http\JsonResponse;
use Api\Framework\Kernel\Services\JwtManager;


/**
 * Cette classe abstraite est utilisée pour les classes Controller de l'application.
 * Elle permet de factoriser le code des classes Controller.
 */
abstract class AbstractController
{
    /**
     * Cette méthode permet d'envoyer une réponse JSON au client.
     * Elle est final pour éviter qu'elle soit redéfinie dans les classes qui l'étendent.
     * Elle utilise la classe JsonResponse pour envoyer une réponse JSON au client.
     *
     * @param array $vars
     * @return void
     */
    public final function send(array $vars): JsonResponse
    {
        return new JsonResponse($vars, 200);
    }

    /**
     * Cette méthode permet de récupérer les données envoyées par le client.
     * Elle est final pour éviter qu'elle soit redéfinie dans les classes qui l'étendent.
     * Elle utilise la méthode statique file_get_contents de la classe Request pour récupérer les données envoyées par le client.
     *
     * @return array
     */
public final function getDecodedToken(): mixed
{
    $manager = new JwtManager();
    $headers = getallheaders();
    $token = $headers['Authorization'];
    $token = explode(' ', $token);
    $token = $token[1];
    return $token ? (array) $manager->decode($token)["data"] : [];
}



}
