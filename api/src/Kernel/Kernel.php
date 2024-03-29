<?php

namespace Api\Framework\Kernel;

// on inclut la classe Dotenv pour charger les variables d'environnement qui est une dépendance
// installer via composer dans notre application
use Api\Framework\Kernel\Utils\Utils;

use Dotenv\Dotenv;

// la classe Kernel est un singleton, on ne peut donc pas l'instancier directement
// on utilisera la méthode statique getInstance pour récupérer l'instance unique de notre classe Kernel
// si l'instance n'existe pas, on l'instancie
// si elle existe, on la retourne
// cela permet de s'assurer qu'il n'existe qu'une seule instance de notre classe Kernel au cours de l'exécution de notre application
class Kernel
{

    private static ?Kernel $instance = null;


    public static final function getInstance(): ?Kernel
    {
        if (self::$instance === null) {
            self::$instance = new Kernel();
        }
        return self::$instance;
    }


    public final function boot(): void
    {
        try {
            $this->loadEnv(); // on charge les variables d'environnement
            $this->registerRoutes(); // on charge les routes de notre application
            $this->loadRequestedRoute(); // on charge la route demandée par l'utilisateur
        } catch (\Throwable $e) {
            Exception\ExceptionManager::send($e); // si une erreur survient, on l'envoie à notre gestionnaire d'exceptions
        } finally {
            exit;
        }
    }

    private function loadEnv(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".."); // on créé une instance de la classe Dotenv
        $dotenv->load(); // on charge les variables d'environnement du fichier .env dans la superglobale $_ENV
    }



    private function registerRoutes(): void
    {
        ApiRouter::registerControllerEndpoints(); // on charge les routes des contrôleurs de notre application
        ApiRouter::registerResourceEndpoints(); // on charge les routes des ressources de notre application
    }

    private function loadRequestedRoute(): void
    {
        try {
            ApiRouter::registerUsersEntities();
            if (str_contains(Utils::getUrn(), 'resources')) {
                ApiRouter::loadResourceEndPoint();
            } else {
                ApiRouter::loadControllerEndpoint();
            }
        } catch (\Throwable $e) {
            Exception\ExceptionManager::send($e); // si une erreur survient, on l'envoie à notre gestionnaire d'exceptions
        }
    }





}