<?php

namespace Api\Framework\App\Entity;

use Api\Framework\Kernel\Attributes\ApiResource;
use Api\Framework\Kernel\Http\Methods\Get;

#[ApiResource('users', [])]
class User
{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $roles = null;
    private ?string $password = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getRoles(): ?string
    {
        return $this->roles;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setRoles(string $roles): void
    {
        $this->roles = $roles;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}