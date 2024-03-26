<?php

namespace Api\Framework\Kernel\Services;

use Api\Framework\Kernel\Abstract\AbstractService;
use Api\Framework\Kernel\Exception\ExceptionManager;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class JwtManager extends AbstractService
{

    static string $privateKey;
    static string $publicKey;

    public function __construct()
    {
        date_default_timezone_set('UTC');
        self::$privateKey = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' .DIRECTORY_SEPARATOR  .'jwt'.DIRECTORY_SEPARATOR  .'private.pem');
        self::$publicKey = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..'. DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' .DIRECTORY_SEPARATOR  .'jwt'.DIRECTORY_SEPARATOR  .'public.pem');
        parent::__construct(get_class($this));
    }

    public function encode(array $data): string
    {
        $payload = [
            "iss" => $_SERVER['HTTP_HOST'],
            "iat" => time(), // issued at
            "nbf" => time(), // not before
            "exp" => time() + (2 * 60 * 60), // 2 hours
            "data" => $data
        ];
        return JWT::encode($payload, self::$privateKey, 'RS256');
    }

    public function decode(string $token): array
    {
        try {
            $decoded = (array)JWT::decode($token, new Key(self::$publicKey, 'RS256'));
            if ($decoded['exp'] < time()) {
                ExceptionManager::send(new \Exception('Token expired'));
            }
            return $decoded;
        } catch (\Exception $e) {
            ExceptionManager::send(new \Exception('Invalid token'));
        }
        return [];
    }

}