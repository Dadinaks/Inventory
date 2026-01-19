<?php

namespace Dadinaks\Role\Application\UseCase;

use Dadinaks\Role\Adapter\Dto\OutputDto;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;

final class GetOneRole
{
    public function __construct(
        private readonly RepositoryInterface $repository
    ) {}

    public function execute(string $uid): OutputDto
    {
        $role = $this->repository->findByUid($uid);

        if (!$role) {
            throw new \DomainException(sprintf('Role with %s uid not found.', $uid));
        }

        return new OutputDto(
            uid: $role->getUid(),
            role: $role->getRole(),
            label: $role->getLabel(),
            description: $role->getDescription(),
        );
    }
}
