<?php

namespace Api\Framework\Kernel\Auth;

interface UserInterface
{
    public function getId(): ?int;
    public function getEmail(): ?string;
    public function getPwd(): ?string;
    public function getRoles(): ?string;
}