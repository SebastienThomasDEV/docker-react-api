<?php

namespace Api\Framework\App\Entity;

use Api\Framework\Kernel\Attributes\ApiResource;
use Api\Framework\Kernel\Http\Operations;

#[ApiResource(
    resource: 'users',
    operations:
    [
        Operations::GET,
        Operations::POST
    ]
)]
class User
{
    private ?int $id = null;
    private ?string $nom = null;
    private ?string $prenom = null;
    private ?string $roles = null;

    private ?string $mdp = null;
    private ?string $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function getRoles(): ?string
    {
        return $this->roles;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }
}