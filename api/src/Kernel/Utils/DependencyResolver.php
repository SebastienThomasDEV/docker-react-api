<?php

namespace Api\Framework\Kernel\Utils;


// Cette classe est un conteneur de services
// Elle permet de résoudre les dépendances des classes injectées dans les paramètres des méthodes d'un contrôleur
// Elle reçoit un tableau qui contient les dépendances non instanciées
// Elle instancie ces dépendances et les retourne dans un tableau associatif
// La clé du tableau associatif est le nom de la dépendance
// ce qui permet d'éviter l'instanciation de plusieurs fois la même dépendance
// et de pouvoir réutiliser la même instance de la dépendance dans plusieurs classes
abstract class DependencyResolver
{

    // on déclare une propriété privée et statique pour stocker les services instanciés
    private static array $services = [];

    // on déclare une méthode statique et publique pour résoudre les dépendances
    // cette méthode va recevoir un tableau qui contient les dépendances non instanciées
    // exemple : ['db' => 'App\Services\Database', 'mailer' => 'App\Services\Mailer']
    // on va parcourir ce tableau et instancier chaque dépendance
    // Grace à la réflexion, on va pouvoir instancier une classe à partir de son nom de classe
    public static function resolve(array $parameters): array
    {
        foreach ($parameters as $key => $file_path) {
            try {
                // on utilise la classe ReflectionClass pour instancier une classe à partir de son nom de classe
                $class = new \ReflectionClass($file_path);
                // on appelle la méthode newInstance de notre instance de ReflectionClass pour instancier la classe
                $class = $class->newInstance();
                // maintenant que la classe est instanciée, on la stocke dans notre tableau de services
                self::$services[$key] = $class;
            } catch (\ReflectionException $e) {
                echo $e->getMessage();
            }
        }
        // on retourne le tableau de services qui sera injecter dans les paramètres des méthodes des contrôleurs
        // il sera donc possible d'utiliser les services instanciés dans les méthodes des contrôleurs
        // c'est possible car on utilise l'opérateur (...) pour transformer le tableau associatif en liste de paramètres
        return self::$services;
    }

}