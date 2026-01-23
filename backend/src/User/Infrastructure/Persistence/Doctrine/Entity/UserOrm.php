<?php

namespace Dadinaks\User\Infrastructure\Persistence\Doctrine\Entity;

use Dadinaks\Role\Infrastructure\Persistence\Doctrine\Entity\RoleOrm as Role;
use Dadinaks\User\Domain\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
final class UserOrm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'uuid', unique: true)]
    private string $uid;

    #[ORM\Column(length: 255)]
    private string $firstname;

    #[ORM\Column(length: 255)]
    private string $lastname;

    #[ORM\Column(length: 255, unique: true)]
    private string $username;

    #[ORM\Column(length: 255)]
    private string $password;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\Column(type: 'boolean')]
    private bool $isConnected = false;

    #[ORM\Column(type: 'boolean')]
    private bool $isDeleted = false;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $updatedAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\ManyToOne(targetEntity: Role::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Role $role;


    public static function fromDomain(User $user, Role $roleOrm): self
    {
        $orm = new self();
        $orm->uid           = $user->getUid();
        $orm->firstname     = $user->getFirstname();
        $orm->lastname      = $user->getLastname();
        $orm->username      = $user->getUserIdentifier();
        $orm->password      = $user->getPassword();
        $orm->isActive      = $user->getIsActive();
        $orm->isConnected   = $user->getIsConnected();
        $orm->isDeleted     = $user->getIsDeleted();
        $orm->createdAt     = $user->getCreatedAt();
        $orm->updatedAt     = $user->getUpdatedAt();
        $orm->deletedAt     = $user->getDeletedAt();
        $orm->role          = $roleOrm;

        return $orm;
    }

    public function toDomain(): User
    {
        return User::fromState([
            'uid'           => $this->uid,
            'firstname'     => $this->firstname,
            'lastname'      => $this->lastname,
            'username'      => $this->username,
            'password'      => $this->password,
            'isActive'      => $this->isActive,
            'isConnected'   => $this->isConnected,
            'isDeleted'     => $this->isDeleted,
            'createdAt'     => $this->createdAt,
            'updatedAt'     => $this->updatedAt,
            'deletedAt'     => $this->deletedAt,
            'role'          => $this->role->toDomain()
        ]);
    }

    public function setFistname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function setIsConnected(bool $isConnected): void
    {
        $this->isConnected = $isConnected;
    }

    public function setIsDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function setRole(Role $role): void
    {
        $this->role = $role;
    }
}
