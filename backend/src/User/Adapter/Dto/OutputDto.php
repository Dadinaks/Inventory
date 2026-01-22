<?php

namespace Dadinaks\User\Adapter\Dto;

use Dadinaks\Role\Adapter\Dto\OutputDto as RoleOutputDto;
use Dadinaks\Shared\Adapter\Dto\OutputDtoInterface;

final readonly class OutputDto implements OutputDtoInterface
{
    public function __construct(
        public string $uid,
        public string $firstname,
        public string $lastname,
        public string $username,
        public bool $isActive,
        public bool $isConnected,
        public \DateTimeImmutable $createdAt,
        public ?\DateTimeImmutable $updatedAt,
        public ?\DateTimeImmutable $deletedAt,
        public RoleOutputDto $role,
    ) {}

    public function toArray(): array
    {
        return [
            'uid'           => $this->uid,
            'firstname'     => $this->firstname,
            'lastname'      => $this->lastname,
            'username'      => $this->username,
            'isActive'      => $this->isActive,
            'isConnected'   => $this->isConnected,
            'createdAt'     => $this->createdAt,
            'updatedAt'     => $this->updatedAt,
            'deletedAt'     => $this->deletedAt,
            'roles'         => $this->role->toArray(),
        ];
    }
}
