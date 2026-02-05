<?php

namespace Dadinaks\Exits\Application\UseCase;

use Dadinaks\Exits\Adapter\Dto\OutputDto;
use Dadinaks\Product\Adapter\Dto\Shared\OutputDto as ProductDto;
use Dadinaks\User\Adapter\Dto\Shared\OutputDto as UserDto;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;

final class ListExits
{
    public function __construct(
        private readonly RepositoryInterface $repository
    ) {}

    public function execute(): array
    {
        $exits = $this->repository->findAll();

        if (empty($exits)) {
            throw new \DomainException('No exits found in the system.');
        }

        return array_map(
            fn($exits) => new OutputDto(
                uid: $exits->getUid(),
                quantity: $exits->getQuantity(),
                isDeleted: $exits->isDeleted(),
                createdAt: $exits->getCreatedAt(),
                updatedAt: $exits->getUpdatedAt(),
                deletedAt: $exits->getDeletedAt(),
                product: ProductDto::fromEntity(product: $exits->getProduct()),
                createdBy: UserDto::fromEntity(user: $exits->getCreatedBy()),
                updatedBy: $exits->getUpdatedBy() ? UserDto::fromEntity(user: $exits->getUpdatedBy()) : null,
                deletedBy: $exits->getDeletedBy() ? UserDto::fromEntity(user: $exits->getDeletedBy()) : null,
            ),
            $exits
        );
    }
}
