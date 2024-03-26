<?php

namespace Api\Framework\Kernel\Services;

use Api\Framework\Kernel\Abstract\AbstractService;

class PasswordHasher extends AbstractService
{
    public function __construct()
    {
        parent::__construct(get_class($this));
    }

    public final static function hash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public final static function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

}