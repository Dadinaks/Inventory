<?php

namespace Dadinaks\User\Domain\Entity;

use Dadinaks\Role\Domain\Entity\Role;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

final class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    private string $uid;

    private string $firstname;

    private string $lastname;

    private string $username;

    private string $password;

    private Role $roles;

    public function __construct(string $firstname, string $lastname, string $username, string $password, Role $roles)
    {
        $this->uid = Uuid::v7()->toString();
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->username = $username;
        $this->password = $password;
        $this->roles = $roles;
    }

    public function getUid(): string
    {
        return $this->uid;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return [$this->roles->getRole()];
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}
