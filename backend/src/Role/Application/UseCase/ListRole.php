<?php

namespace Dadinaks\Role\Application\UseCase;

use Dadinaks\Role\Adapter\Dto\OutputDto;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;

final class ListRole
{
    public function __construct(
        private readonly RepositoryInterface $repository
    ) {}

    public function execute(): array
    {
        $roles = $this->repository->findAll();

        if (empty($roles)) {
            throw new \DomainException('No roles found in the system.');
        }

        return array_map(
            fn($roles) => new OutputDto(
                uid: $roles->getUid(),
                role: $roles->getRole(),
                label: $roles->getLabel(),
                description: $roles->getDescription(),
            ),
            $roles
        );
    }
}
