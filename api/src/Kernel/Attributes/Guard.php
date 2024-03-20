<?php

namespace Api\Framework\Kernel\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
class Guard
{

    public function __construct(private readonly string $auth_type = "jwt")
    {
    }



    public final function check(): bool
    {
        $auth_mode = match ($this->auth_type) {
            "jwt" => 'Api\\Framework\\Kernel\\Auth\\JwtTokenManager'
        };
        return $auth_mode::check();

    }
}