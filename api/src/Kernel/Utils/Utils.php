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
        return $_SERVER['REQUEST_URI'];

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
        $resourceIdentifier = basename($urn, $resource);
        if (is_null($resourceIdentifier) || is_numeric($resourceIdentifier) === false || empty($resourceIdentifier)) {
            return null;
        } else {
            return (int) $resourceIdentifier;
        }
    }



}