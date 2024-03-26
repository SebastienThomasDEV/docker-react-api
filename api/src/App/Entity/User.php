<?php

namespace Api\Framework\App\Entity;

use Api\Framework\Kernel\Attributes\ApiResource;
use Api\Framework\Kernel\Auth\UserInterface;
use Api\Framework\Kernel\Http\Methods\Get;

#[ApiResource('users')]
class User implements UserInterface
{
    private ?int $id = null;

    private ?string $email = null;
    private ?string $name = null;
    private ?string $surname = null;
    private ?string $roles = null;
    private ?string $pwd = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getRoles(): ?string
    {
        return $this->roles;
    }

    public function getPwd(): ?string
    {
        return $this->pwd;
    }

    /**
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $roles
     * @return void
     */
    public function setRoles(string $roles): void
    {
        $this->roles = $roles;
    }


    /**
     * @param string|null $pwd
     * @return void
     */
    public function setPwd(?string $pwd): void
    {
        $this->pwd = $pwd;
    }

    /**
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }


    /**
     * @param string $surname
     * @return void
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }


}