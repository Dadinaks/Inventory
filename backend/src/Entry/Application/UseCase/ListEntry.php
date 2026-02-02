<?php

namespace Dadinaks\Entry\Application\UseCase;

use Dadinaks\Entry\Adapter\Dto\OutputDto;
use Dadinaks\Product\Adapter\Dto\Shared\OutputDto as ProductDto;
use Dadinaks\User\Adapter\Dto\Shared\OutputDto as UserDto;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;

final class ListEntry
{
    public function __construct(
        private readonly RepositoryInterface $repository
    ) {}

    public function execute(): array
    {
        $entry = $this->repository->findAll();

        if (empty($entry)) {
            throw new \DomainException('No entries found in the system.');
        }

        return array_map(
            fn($entry) => new OutputDto(
                uid: $entry->getUid(),
                quantity: $entry->getQuantity(),
                isDeleted: $entry->isDeleted(),
                createdAt: $entry->getCreatedAt(),
                updatedAt: $entry->getUpdatedAt(),
                deletedAt: $entry->getDeletedAt(),
                product: ProductDto::fromEntity($entry->getProduct()),
                createdBy: UserDto::fromEntity($entry->getCreatedBy()),
                updatedBy: $entry->getUpdatedBy() ? UserDto::fromEntity($entry->getUpdatedBy()) : null,
                deletedBy: $entry->getDeletedBy() ? UserDto::fromEntity($entry->getDeletedBy()) : null,
            ),
            $entry
        );
    }
}
