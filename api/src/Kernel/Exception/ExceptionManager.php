<?php

namespace Api\Framework\Kernel\Exception;

use Api\Framework\Kernel\Http\JsonResponse;

class ExceptionManager
{

    public static function send(\Throwable $e): JsonResponse
    {
        if ($_ENV["APP_ENV"] === "DEV") {
            return new JsonResponse([
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace()
            ], 500);
        } else if ($_ENV["APP_ENV"] === "PROD") {
            return new JsonResponse([
                'message' => 'An error occurred while processing your request. Please try again later.'
            ], 500);
        } else {
            return new JsonResponse([
                'message' => 'APP_ENV is not set. Please set APP_ENV to DEV or PROD.'
            ], 500);
        }
    }

}