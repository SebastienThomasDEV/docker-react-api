<?php

namespace Api\Framework\Kernel\Auth;

interface AuthTypeInterface
{
    public static function check(): bool;

}