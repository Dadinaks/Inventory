<?php

namespace Dadinaks\User\Adapter\Dto;

use Dadinaks\Shared\Adapter\Dto\OutputDtoInterface;
use Dadinaks\Role\Adapter\Dto\OutputDto as RoleOutputDto;

final class OutputLogoutDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $uid,
        public readonly string $username,
        public readonly bool $isActive,
        public readonly bool $isConnected,
        public readonly RoleOutputDto $role,
    ) {}

    public function toArray(): array
    {
        return [
            'uid'           => $this->uid,
            'username'      => $this->username,
            'isActive'      => $this->isActive,
            'isConnected'   => $this->isConnected,
            'role'          => $this->role->toArray()
        ];
    }
}
