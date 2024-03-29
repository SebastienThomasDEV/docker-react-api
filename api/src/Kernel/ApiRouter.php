<?php

namespace Api\Framework\Kernel;

use Api\Framework\Kernel\Exception\ExceptionManager;
use Api\Framework\Kernel\Utils\DependencyResolver;
use Api\Framework\Kernel\Utils\Utils;


abstract class ApiRouter
{
    private static array $controllerEndpoints = [];
    private static array $resources = [];

    public static function registerControllerEndpoints(): void
    {
        $dir = opendir(
            __DIR__ .
            DIRECTORY_SEPARATOR .
            '..' . DIRECTORY_SEPARATOR .
            'App' . DIRECTORY_SEPARATOR .
            'Controller');
        while ($file_path = readdir($dir)) {
            if ($file_path !== '.' && $file_path !== '..') {
                $file_path = str_replace('.php', '', $file_path);
                $file_path = "Api\\Framework\\App\\Controller\\" . $file_path;
                try {
                    $class = new \ReflectionClass($file_path);
                    $methods = $class->getMethods();
                    foreach ($methods as $method) {
                        $attributes = $method->getAttributes();
                        $parameters = $method->getParameters();
                        $endpoint = array_filter($attributes, function ($attribute) {
                            return $attribute->getName() === 'Api\\Framework\\Kernel\\Attributes\\Endpoint';
                        });
                        $guard = array_filter($attributes, function ($attribute) {
                            return $attribute->getName() === 'Api\\Framework\\Kernel\\Attributes\\Guard';
                        });
                        if (count($endpoint) > 0) {
                            $endpoint = array_values($endpoint)[0]->newInstance();
                            $endpoint->setController($file_path);
                            $endpoint->setMethod($method->getName());
                            foreach ($parameters as $parameter) {
                                if (!Utils::isPrimitiveFromString($parameter->getType())) {
                                    $endpoint->setParameter($parameter->getName(), $parameter->getType());
                                } else {
                                    ExceptionManager::send(new \Exception('A non-object type parameter has been found, try to replace by an service', 500));
                                }
                            }
                            if (count($guard) > 0) {
                                $endpoint->setGuard($guard[0]->newInstance());
                            }
                            self::$controllerEndpoints[] = $endpoint;
                        }
                    }
                } catch (\ReflectionException $e) {
                    ExceptionManager::send(new \Exception($e->getMessage(), $e->getCode()));
                }
            }
        }
        closedir($dir);
    }


    public static function registerResourceEndpoints(): void
    {
        $dir = opendir(__DIR__
            . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            .'App'. DIRECTORY_SEPARATOR
            .'Entity');
        while ($file_path = readdir($dir)) {
            if ($file_path !== '.' && $file_path !== '..') {
                $className = str_replace('.php', '', $file_path);
                $file_path = "Api\\Framework\\App\\Entity\\" . $className;
                try {
                    $class = new \ReflectionClass($file_path);
                    $attributes = $class->getAttributes();
                    if (count($attributes) > 0) {
                        foreach ($attributes as $attribute) {
                            if ($attribute->getName() === 'Api\\Framework\\Kernel\\Attributes\\ApiResource') {
                                $resource = $attribute->newInstance();
                                foreach ($resource->getOperations() as $operation) {
                                    $operation->build($resource->getResource());
                                }
                                self::$resources[$className] = $resource->getOperations();
                            }
                        }
                    }
                } catch (\ReflectionException $e) {
                    ExceptionManager::send(new \Exception($e->getMessage(), $e->getCode()));
                }
            }
        }
        closedir($dir);
    }

    public static function loadControllerEndpoint(): void
    {
        foreach (self::$controllerEndpoints as $endpoint) {
            if (Utils::getUrn() === $endpoint->getPath()) {
                if ($endpoint->getRequestMethod() === Utils::getRequestedMethod()) {
                    if (class_exists($endpoint->getController())) {
                        if ($endpoint->getGuard()) {
                            if (!$endpoint->getGuard()->handle()) {
                                ExceptionManager::send(new \Exception('Unauthorized', 401));
                            }
                        }
                        $controller = new \ReflectionClass($endpoint->getController());
                        try {
                            $controller = $controller->newInstance();
                            if (method_exists($controller, $endpoint->getMethod())) {
                                $method = $endpoint->getMethod();
                                $services = DependencyResolver::resolve($endpoint->getParameters());
                                $controller->$method(...$services);
                            } else {
                                ExceptionManager::send(new \Exception('Controller method not found', 404));
                            }
                        } catch (\ReflectionException $e) {
                            ExceptionManager::send(new \Exception($e->getMessage(), $e->getCode()));
                        }
                    } else {
                        ExceptionManager::send(new \Exception('Controller not found', 404));
                    }
                } else {
                    ExceptionManager::send(new \Exception('Method not allowed for this endpoint', 405));
                }
            }
        }
        ExceptionManager::send(new \Exception('Any endpoint found', 404));
    }

    public static function loadResourceEndPoint(): void
    {
        foreach (self::$resources as $resource) {
            $availableMethods = [];
            foreach ($resource as $availableMethod) {
                $methodName = explode('\\', get_class($availableMethod));
                $methodName = strtoupper(end($methodName));
                $availableMethods[$methodName] = $availableMethod;
            }
            if ($operation = $availableMethods[Utils::getRequestedMethod()] ?? null) {
                if (str_contains(Utils::getUrn(), $operation->getResource())) {
                    if ($id = Utils::getResourceIdentifierFromUrn($operation->getResource())) {
                        $operation->execute($id);
                    } else {
                        $operation->execute();
                    }
                } else {
                    ExceptionManager::send(new \Exception('Resource not found', 404));
                }
            } else {
                ExceptionManager::send(new \Exception('Resource operation not found', 404));
            }
        }
        ExceptionManager::send(new \Exception('Resource endpoint not found', 404));
    }


    public static function registerUsersEntities():void {
        $dir = opendir(__DIR__
            . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            .'App'. DIRECTORY_SEPARATOR
            .'Entity');
        while ($file_path = readdir($dir)) {
            if ($file_path !== '.' && $file_path !== '..') {
                $className = str_replace('.php', '', $file_path);
                $file_path = "Api\\Framework\\App\\Entity\\" . $className;
                try {
                    $class = new \ReflectionClass($file_path);
                    foreach ($class->getInterfaceNames() as $interface) {
                        if ($interface === 'Api\Framework\Kernel\Auth\UserInterface' && !isset($_ENV["user"])) {
                            $userEntity = [
                                'class' => $file_path,
                                'repository' => str_replace('Entity', 'Repository', $file_path) . 'Repository'
                            ];
                            $_ENV["user"] = $userEntity;
                        } else {
                            ExceptionManager::send(new \Exception('User entity not found', 404));
                        }
                    }
                } catch (\ReflectionException $e) {
                    ExceptionManager::send(new \Exception($e->getMessage(), $e->getCode()));
                }
            }
        }
        closedir($dir);
    }

}