<?php

namespace Api\Framework\Kernel\Utils;

use Api\Framework\Kernel\Exception\ExceptionManager;

abstract class Utils
{

    public static function isPrimitiveFromString(string $type): bool
    {
        return match ($type) {
            'string', 'int', 'float', 'bool', 'array', 'null' => true,
            default => false,
        };
    }

    public static function getUrn(): string
    {
        // get base name of uri use basename method
        $uri = basename($_SERVER['REQUEST_URI']);
        if (basename($uri) === 'public') {
            return '/';
        } else {
            return "/$uri";
        }
    }



    public static function getRequestedMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function sanitizeData(array $data): array
    {
        $sanitizedData = [];
        foreach ($data as $key => $value) {
            $value = trim($value);
            $value = stripslashes($value);
            $value = htmlspecialchars($value);
            $sanitizedData[$key] = $value;
        }
        return $sanitizedData;
    }

    public static function getRequestBody(): array | null
    {
        $rawInput = fopen('php://input', 'r');
        $tempStream = fopen('php://temp', 'r+');
        stream_copy_to_stream($rawInput, $tempStream);
        rewind($tempStream);
        $requestBody = file_get_contents('php://input');
        return json_decode($requestBody, true) ?? [];
    }

    public static function getResourceIdentifierFromUrn(string $resource): int | null
    {
        $urn = self::getUrn();
        $urn = explode('/', $urn);
        $resourceIdentifier = array_search($resource, $urn);
        if ($resourceIdentifier === false) {
            return null;
        } else {
            // On vérifie si l'identifiant de la ressource est un nombre
            // on doit caster la valeur en int pour s'assurer que c'est un nombre
            // si ce n'est pas un nombre, on retourne null
            // /!\ on doit vérifier que la valeur $urn[$resourceIdentifier + 1] castée en int est égale à 0
            return (int) $urn[$resourceIdentifier + 1] === 0 ? null : (int) $urn[$resourceIdentifier + 1];
        }
    }



}