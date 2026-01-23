<?php

namespace Dadinaks\User\Application\UseCase;

use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\User\Adapter\Dto\OutputDto;
use Dadinaks\Role\Adapter\Dto\OutputDto As RoleOutputDto;
use Dadinaks\User\Application\Policy\DeletePolicy;

final class DeleteUser
{
    public function __construct(
        private readonly RepositoryInterface $repository,
        private readonly DeletePolicy $policy,
    ) {}

    public function execute(string $uid)
    {
        $user = $this->repository->findByUid($uid);

        if (!$user) {
            throw new \DomainException(
                sprintf('User with uid: "%s" not found.', $uid)
            );
        }

        if (!$this->policy->canDelete($user)) {
            throw new \DomainException(
                sprintf('User "%s" is already deleted.', $user->getFirstname() . ' ' . $user->getLastname())
            );
        }

        $user->markAsDeleted();
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
