<?php

namespace Api\Framework\Kernel\Auth;

abstract class JwtTokenManager implements AuthTypeInterface
{
    public static function check(): bool
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
        return true;
    }
}