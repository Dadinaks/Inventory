<?php

namespace Dadinaks\User\Application\UseCase;

use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\User\Adapter\Dto\OutputDto;
use Dadinaks\Role\Adapter\Dto\OutputDto as RoleOutputDto;

final class DisabledUser
{
    public function __construct(
        private readonly RepositoryInterface $repository,
    ) {}

    public function execute(string $uid): OutputDto
    {
        $user = $this->repository->findByUid($uid);

        if (!$user) {
            throw new \DomainException(
                sprintf('User with uid: "%s" not found.', $uid)
            );
        }

        if (!$user->getIsActive()) {
            throw new \DomainException('User is already disabled.');
        }

        $user->disable();

        $this->repository->save($user);

        return new OutputDto(
            uid: $user->getUid(),
            firstname: $user->getFirstname(),
            lastname: $user->getLastname(),
            username: $user->getUserIdentifier(),
            isActive: $user->getIsActive(),
            isConnected: $user->getIsConnected(),
            isDeleted: $user->getIsDeleted(),
            role: new RoleOutputDto(
                uid: $user->getRole()->getUid(),
                role: $user->getRole()->getRole(),
                label: $user->getRole()->getLabel(),
                description: $user->getRole()->getDescription(),
            ),
            createdAt: $user->getCreatedAt(),
            updatedAt: $user->getUpdatedAt(),
            deletedAt: $user->getDeletedAt(),
        );
    }
}
