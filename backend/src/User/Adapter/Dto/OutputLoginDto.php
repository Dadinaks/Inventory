<?php

namespace Dadinaks\User\Adapter\Dto;

use Dadinaks\Shared\Adapter\Dto\OutputDtoInterface;
use Dadinaks\Role\Adapter\Dto\OutputDto as RoleOutputDto;

final class OutputLoginDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $uid,
        public readonly string $firstname,
        public readonly string $lastname,
        public readonly string $username,
        public readonly bool $isActive,
        public readonly bool $isConnected,
        public readonly RoleOutputDto $role,
        public readonly string $token,
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
            'roles'         => $this->role->toArray(),
            'token'         => $this->token,
        ];
    }
}
