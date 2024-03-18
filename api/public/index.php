<?php

// ce fichier index.php est le point d'entrée de notre application
// c'est lui qui va être appelé par le serveur web
// qu'importe l'adresse demandée par l'utilisateur (URL), c'est ce fichier qui sera exécuté
// on utilise le fichier .htaccess pour rediriger toutes les requêtes vers ce fichier
// c'est donc ici que l'on va instancier notre application et lancer le processus de traitement de la requête
// on active le module réécriture d'URL dans le fichier .htaccess pour que l'URL soit plus jolie et plus simple à lire

// on inclut le fichier autoload.php généré par composer
// il permet de charger toutes les classes de notre application
// sans avoir à les inclure une par une dans nos fichiers
// on peut donc utiliser les classes de notre application sans se soucier de leur emplacement
include_once(dirname(__DIR__) . '/vendor/autoload.php');
use Api\Framework\Kernel\Kernel;
use Api\Framework\Kernel\Exception\ExceptionManager;
// on instancie notre classe Kernel qui est le point d'entrée de notre application MVC
// c'est lui qui va charger les variables d'environnement et les routes de notre application puis traite la requête de l'utilisateur
// on encapsule l'instanciation de notre classe dans un bloc try/catch pour gérer les erreurs
// si une erreur survient, on la capture et on l'envoie à notre gestionnaire d'exceptions
// qui va se charger de renvoyer une réponse d'erreur à l'utilisateur sous forme de JSON
try {
    // la classe Kernel est un singleton, on ne peut donc pas l'instancier directement
    // on utilise la méthode statique getInstance pour récupérer l'instance unique de notre classe Kernel
    // on appelle ensuite la méthode boot pour lancer le processus de traitement de la requête
    $kernel = Kernel::getInstance();
    $kernel->boot();
} catch (Exception $e) {
    ExceptionManager::send($e);
}
