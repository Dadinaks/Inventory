<?php

namespace Dadinaks\User\Application\UseCase;

use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\User\Adapter\Dto\OutputDto;
use Dadinaks\Role\Adapter\Dto\OutputDto as RoleOutputDto;
use Dadinaks\User\Application\Policy\DeletePolicy;

final class RestoreUser
{
    public function __construct(
        private readonly RepositoryInterface $repository,
        private readonly DeletePolicy $policy,
    ) {}

    public function execute(string $uid)
    {
        $user = $this->repository->findByUid(uid: $uid);

        if (!$this->policy->canRestore(user: $user)) {
            throw new \DomainException(
                sprintf('User "%s" is not deleted.', $user->getFirstname() . ' ' . $user->getLastname())
            );
        }

        $user->restore();
        $this->repository->save(entity: $user);

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
