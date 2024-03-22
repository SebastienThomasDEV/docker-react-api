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

    // on déclare une propriété privée et statique pour stocker l'instance unique de notre classe Kernel
    // on déclare cette propriété comme étant de type Kernel (le type de la classe) et nullable (elle peut être null)
    // on l'initialise à null pour indiquer qu'aucune instance n'existe au départ
    // on pourra donc tester si l'instance existe ou non en vérifiant si cette propriété est null ou non
    private static ?Kernel $instance = null;


    // on déclare une méthode statique et publique pour pouvoir l'appeler sans avoir besoin d'instancier la classe
    // cette méthode va nous permettre de récupérer une instance unique de notre classe Kernel
    // si l'instance n'existe pas, on l'instancie et on la stocke dans la propriété $instance
    // pour appeler cette méthode, on utilise le nom de la classe suivi de deux points et de la méthode getInstance
    // par exemple : Kernel::getInstance()
    // vu que qu'on appelle cette methode et cette methode est static, on ne peut pas utiliser $this
    // on utilise donc self:: pour accéder à la propriété $instance de la classe Kernel
    public static final function getInstance(): ?Kernel
    {
        if (self::$instance === null) {
            self::$instance = new Kernel();
        }
        return self::$instance;
    }


    // on créé une methode que je nomme boot qui va lancer le processus de traitement de la requête
    // le traitement de la requête consiste à charger les variables d'environnement, les routes de notre application
    // On appelle la méthode loadEnv de notre classe Kernel pour charger les variables d'environnement
    // On appelle la méthode registerRoutes de notre classe Kernel pour charger les routes de notre application
    // puis à appeler la méthode loadRequestedRoute pour charger la route demandée par l'utilisateur
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

    // on créé une methode que je nomme loadEnv qui va charger les variables d'environnement
    // pour cela, on utilise la classe Dotenv qui est une dépendance installée via composer
    // on créé une instance de la classe Dotenv en lui passant le chemin du fichier .env
    // puis on appelle la méthode load de notre instance Dotenv pour charger les variables d'environnement
    // le fichier .env contient les variables d'environnement de notre application (identifiants de connexion à la base de données, clés secrètes, etc.)
    // ces variables sont stockées dans des paires clé/valeur et sont accessibles via la superglobale $_ENV
    private function loadEnv(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".."); // on créé une instance de la classe Dotenv
        $dotenv->load(); // on charge les variables d'environnement du fichier .env dans la superglobale $_ENV
        // je charge le namespace de mon application pour l'utiliser plus tard
    }


    // on crée une methode que je nomme registerRoutes qui va charger les routes de notre application
    // pour cela, on appelle la méthode statique registerControllerEndpoints de notre classe ApiRouter
    // qui va charger les routes des contrôleurs de notre application
    // puis on appelle la méthode statique registerResourceEndpoints de notre classe ApiRouter
    // qui va automatiquement les routes des ressources de notre application (CRUD) de nos Entités
    // exemple : /users, /users/{id}, /posts, /posts/{id}, etc.
    private function registerRoutes(): void
    {
        ApiRouter::registerControllerEndpoints(); // on charge les routes des contrôleurs de notre application
        ApiRouter::registerResourceEndpoints(); // on charge les routes des ressources de notre application
    }


    // on crée une methode que je nomme loadRequestedRoute qui va charger la route demandée par l'utilisateur
    // pour cela, on appelle la méthode statique load de notre classe ApiRouter
    // qui va charger la route demandée par l'utilisateur en fonction de l'URL demandée
    // si la route demandée n'existe pas, la méthode load de notre classe ApiRouter va renvoyer une erreur 404
    private function loadRequestedRoute(): void
    {
        try {
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