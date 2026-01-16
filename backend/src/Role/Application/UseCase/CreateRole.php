<?php

namespace Dadinaks\Role\Application\UseCase;

use Dadinaks\Role\Adapter\Dto\OutputDto;
use Dadinaks\Role\Domain\Entity\Role;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;

final class CreateRole
{
    public function __construct(
        private readonly RepositoryInterface $repository,
    ) {}


    public function execute(string $role, string $label, ?string $description): OutputDto
    {
        if ($this->repository->findOneBy(['role' => $role])) {
            throw new \DomainException(
                sprintf('Role : "%s" already exists.', $role)
            );
        }

        if ($this->repository->findOneBy(['label' => $label])) {
            throw new \DomainException(
                sprintf('Role with name: "%s" already exists.', $label)
            );
        }

        $roleEntity = new Role(
            role: $role,
            label: $label,
            description: $description,
        );

        $this->repository->save($roleEntity);

        return new OutputDto(
            uid: $roleEntity->getUid(),
            role: $roleEntity->getRole(),
            label: $roleEntity->getLabel(),
            description: $roleEntity->getDescription(),
        );
    }
}
