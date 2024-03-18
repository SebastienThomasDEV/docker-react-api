<?php

namespace Api\Framework\Kernel\Utils;
use Api\Framework\Kernel\Exception\ExceptionManager;

abstract class Serializer
{
    public final static function serialize(mixed $data, string $class): object | null
    {
        try {
            $class = new \ReflectionClass("Api\\Framework\\App\\Entity\\" . $class);
            $object = $class->newInstance();
            foreach ($data as $key => $value) {
                if ($class->hasProperty($key)) {
                    $property = $class->getProperty($key);
                    $property->setAccessible(true);
                    $property->setValue($object, $value);
                } else {
                    throw new \ReflectionException('Property ' . $key . ' not found in class ' . $class->getName());
                }
            }
            return $object;
        } catch (\ReflectionException $e) {
            return ExceptionManager::send($e);
        }
    }

    public final static function serializeAll(array $entities, string $class): array
    {
        $result = [];
        foreach ($entities as $entity) {
            $result[] = self::serialize($entity, $class);
        }
        return $result;
    }

    public final static function unserialize(object $entity): array
    {
        $class = new \ReflectionClass($entity);
        $properties = $class->getProperties();
        $data = [];
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $data[$property->getName()] = $property->getValue($entity);
        }
        return $data;
    }

    public final static function unserializeAll(array $entities): array
    {
        $result = [];
        foreach ($entities as $entity) {
            $result[] = self::unserialize($entity);
        }
        return $result;
    }
}