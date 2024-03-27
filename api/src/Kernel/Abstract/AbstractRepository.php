<?php

namespace Api\Framework\Kernel\Abstract;

use Api\Framework\Kernel\Model\Model;
use Api\Framework\Kernel\Utils\Serializer;


// Cette classe est une classe abstraite qui permet de factoriser le code des classes Repository de l'application.
// Elle permet de définir le nom de l'entité associée à la classe Repository qui l'étend.
// Elle permet aussi de définir des méthodes génériques pour les classes Repository.
// dans son constructeur, elle récupère le nom de la classe Repository qui l'étend
// elle extrait le nom de l'entité associée à la classe Repository
// elle stocke ce nom dans une propriété privée
abstract class AbstractRepository
{

    // on déclare une propriété privée pour stocker le nom de l'entité associée à la classe Repository
    private string $entity;

    // Dans son constructeur, elle récupère le nom de la classe Repository qui l'étend
    // on utilise la fonction get_class pour récupérer le nom de la classe Repository
    // celle ci affiche le nom complet de la classe enfant qui l'étend et non pas le nom de la classe AbstractRepository
    // car get_class retourne le nom complet de la classe de l'objet qui l'appelle
    public function __construct()
    {
        // on récupère le nom complet de la classe Repository qui l'étend
        // exemple : App\Repositories\UserRepository
        $arrayDir = explode("\\", get_class($this));
        // on extrait le nom de la classe Repository
        // exemple : UserRepository
        $repositoryName = end($arrayDir);
        // on extrait le nom de l'entité associée à la classe Repository
        // exemple : user
        $this->entity = strtolower(substr($repositoryName, 0, strpos($repositoryName, 'Repository')));
        $this->entity = Model::getInstance()->getTables()[$this->entity];
    }

    public final function save(object $entity): void
    {
        if (!is_null($entity->getId())) {
            $class = new \ReflectionClass($entity);
            $properties = $class->getProperties();
            $data = [];
            foreach ($properties as $property) {
                $property->setAccessible(true);
                $data[$property->getName()] = $property->getValue($entity);
            }
            $sql = "UPDATE " . $this->entity . " SET ";
            foreach ($data as $key => $value) {
                if ($key === 'id') continue;
                $sql .= $key . " = :" . $key . ", ";
            }
            $sql = substr($sql, 0, -2);
            $sql .= " WHERE id = :id";
            Model::getInstance()->query($sql, $data);
        } else {
            $class = new \ReflectionClass($entity);
            $properties = $class->getProperties();
            $data = [];
            foreach ($properties as $property) {
                if ($property->getName() === 'id') continue;
                $property->setAccessible(true);
                $data[$property->getName()] = $property->getValue($entity);
            }
            $sql = "INSERT INTO " . $this->entity . " (" . implode(',', array_keys($data)) . ") VALUES (:" . implode(',:', array_keys($data)) . ")";
            Model::getInstance()->query($sql, $data);
        }
    }

    public final function findById(int $id): object
    {
        $sql = "SELECT * FROM " . $this->entity . " WHERE id = :id";
        return Serializer::serialize(Model::getInstance()->query($sql, ['id' => $id])[0], ucfirst($this->entity));
    }

    public final function findAll(): array
    {
        $sql = "SELECT * FROM " . $this->entity;
        return Model::getInstance()->query($sql);
    }

    public final function findBy(array $criteria): array
    {
        $sql = "SELECT * FROM " . $this->entity . " WHERE ";
        foreach ($criteria as $key => $value) {
            $sql .= $key . " = :" . $key . " AND ";
        }
        $sql = substr($sql, 0, -5);
        return Serializer::serializeAll(Model::getInstance()->query($sql, $criteria), ucfirst($this->entity));
    }

    public final function findOneBy(array $criteria): object | array
    {
        $sql = "SELECT * FROM " . $this->entity . " WHERE ";
        foreach ($criteria as $key => $value) {
            $sql .= $key . " = :" . $key . " AND ";
        }

        $sql = substr($sql, 0, -5);
        $result = Model::getInstance()->query($sql, $criteria);
        if (empty($result)) {
            return [];
        }
        return Serializer::serialize(Model::getInstance()->query($sql, $criteria)[0], ucfirst($this->entity));
    }

    public function delete(int $id): void
    {
        $sql = "DELETE FROM " . $this->entity . " WHERE id = :id";
        Model::getInstance()->query($sql, ['id' => $id]);
    }


    public final function createQuery(string $sql, array $params = []): array
    {
        return Model::getInstance()->query($sql, $params);
    }


}