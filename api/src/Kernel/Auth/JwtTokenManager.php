<?php

namespace Api\Framework\Kernel\Auth;

use Api\Framework\Kernel\Exception\ExceptionManager;
use Api\Framework\Kernel\Services\JwtManager;

abstract class JwtTokenManager implements AuthTypeInterface
{
    public static function check(mixed $args = null): bool
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            return false;
        } else {
            $token = $headers['Authorization'];
            $token = explode(" ", $token);
            if (count($token) !== 2) {
                return false;
            }
            if ($token[0] !== "Bearer") {
                return false;
            }
            $token = $token[1];
            if($token === "" || $token === null){
                return false;
            }
        }
        $manager = new JwtManager();
        $userData = $manager->decode($token);
        if (!$userData['id']) {
            ExceptionManager::send(new \Exception('Decoded token does not contain user id', 401));
        } else {
            $repository = new $_ENV["user"]["repository"];
            $user = $repository->findById($userData['id']);
            if (!$user) {
                ExceptionManager::send(new \Exception('User with id ' . $userData['id'] . ' not found', 404));
            } else {
                $_ENV["user"]["entity"] = $user;
            }
        }

        return true;
    }
}