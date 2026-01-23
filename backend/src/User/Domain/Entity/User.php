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

    private bool $isActive = true;

    private bool $isConnected = false;

    private bool $isDeleted = false;

    private \DateTimeImmutable $createdAt;

    private ?\DateTimeImmutable $updatedAt = null;

    private ?\DateTimeImmutable $deletedAt = null;

    private Role $role;

    public function __construct(string $firstname, string $lastname, string $username, string $password, Role $role)
    {
        $this->uid = Uuid::v7()->toString();
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->username = $username;
        $this->password = $password;
        $this->createdAt = new \DateTimeImmutable();
        $this->isActive = true;
        $this->isConnected = false;
        $this->isDeleted = false;
        $this->role = $role;
    }

    public static function fromState(array $state): self
    {
        $user = new self($state['firstname'], $state['lastname'], $state['username'], $state['password'], $state['role']);
        $user->uid = $state['uid'];
        $user->isActive = $state['isActive'];
        $user->isConnected = $state['isConnected'];
        $user->isDeleted = $state['isDeleted'];
        $user->createdAt = $state['createdAt'];
        $user->updatedAt = $state['updatedAt'] ?? null;
        $user->deletedAt = $state['deletedAt'] ?? null;

        return $user;
    }

    public function disable(): bool
    {
        $this->updatedAt = new \DateTimeImmutable();
        return $this->isActive = false;
    }

    public function enable(): bool
    {
        $this->updatedAt = new \DateTimeImmutable();
        return $this->isActive = true;
    }

    public function markAsDeleted(): bool
    {
        $this->updatedAt = new \DateTimeImmutable();
        $this->deletedAt = new \DateTimeImmutable();
        $this->isActive = false;
        return $this->isDeleted = true;
    }

    public function restore(): bool
    {
        $this->updatedAt = new \DateTimeImmutable();
        $this->deletedAt = null;
        $this->isActive = true;
        return $this->isDeleted = false;
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
        return [$this->role->getRole()];
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function getIsConnected(): bool
    {
        return $this->isConnected;
    }

    public function getIsDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }
}
