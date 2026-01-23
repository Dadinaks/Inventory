<?php

namespace Dadinaks\User\Application\UseCase;

use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\User\Adapter\Dto\OutputDto;
use Dadinaks\Role\Adapter\Dto\OutputDto as RoleOutputDto;

final class ListUser
{
    public function __construct(
        private readonly RepositoryInterface $userRepository,
    ) {}

    public function execute(): array
    {
        $users = $this->userRepository->findAll();

        if (empty($users)) {
            throw new \DomainException('No user found in the system.');
        }

        return array_map(
            fn($users) => new OutputDto(
                uid: $users->getUid(),
                firstname: $users->getFirstname(),
                lastname: $users->getLastname(),
                username: $users->getUserIdentifier(),
                isActive: $users->getIsActive(),
                isConnected: $users->getIsConnected(),
                isDeleted: $users->getIsDeleted(),
                role: new RoleOutputDto(
                    uid: $users->getRole()->getUid(),
                    role: $users->getRole()->getRole(),
                    label: $users->getRole()->getLabel(),
                    description: $users->getRole()->getDescription(),
                ),
                createdAt: $users->getCreatedAt(),
                updatedAt: $users->getUpdatedAt(),
                deletedAt: $users->getDeletedAt(),
            ),
            $users
        );
    }
}
