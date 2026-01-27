<?php

namespace Dadinaks\User\Adapter\Dto\Shared;

use Dadinaks\Role\Adapter\Dto\Shared\OutputDto as RoleOutputDto;
use Dadinaks\Shared\Adapter\Dto\OutputDtoInterface;
use Dadinaks\User\Domain\Entity\User;

final readonly class OutputDto implements OutputDtoInterface
{
    public function __construct(
        public string $uid,
        public string $firstname,
        public string $lastname,
        public bool $isActive,
        public bool $isConnected,
        public bool $isDeleted,
        public RoleOutputDto $role,
    ) {}

    public function toArray(): array
    {
        return [
            'uid'           => $this->uid,
            'firstname'     => $this->firstname,
            'lastname'      => $this->lastname,
            'isActive'      => $this->isActive,
            'isConnected'   => $this->isConnected,
            'isDeleted'     => $this->isDeleted,
            'role'          => $this->role->toArray(),
        ];
    }

    public static function fromEntity(?User $user): ?self
    {
        if (!$user) return null;

        return new self(
            uid: $user->getUid(),
            firstname: $user->getFirstname(),
            lastname: $user->getLastname(),
            isActive: $user->getIsActive(),
            isConnected: $user->getIsConnected(),
            isDeleted: $user->getIsDeleted(),
            role: RoleOutputDto::fromEntity($user->getRole())
        );
    }
}
