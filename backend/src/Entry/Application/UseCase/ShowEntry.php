<?php

namespace Dadinaks\Entry\Application\UseCase;

use Dadinaks\Entry\Adapter\Dto\OutputDto;
use Dadinaks\Product\Adapter\Dto\Shared\OutputDto as ProductDto;
use Dadinaks\User\Adapter\Dto\Shared\OutputDto as UserDto;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;

final class ShowEntry
{
    public function __construct(
        private readonly RepositoryInterface $repository
    ) {}

    public function execute(string $uid): OutputDto
    {
        $entry = $this->repository->findByUid(uid: $uid);

        if (!$entry) {
            throw new \DomainException(sprintf('Entry with %s uid not found.', $uid));
        }

        return new OutputDto(
            uid: $entry->getUid(),
            quantity: $entry->getQuantity(),
            isDeleted: $entry->isDeleted(),
            createdAt: $entry->getCreatedAt(),
            updatedAt: $entry->getUpdatedAt(),
            deletedAt: $entry->getDeletedAt(),
            product: ProductDto::fromEntity(product: $entry->getProduct()),
            createdBy: UserDto::fromEntity(user: $entry->getCreatedBy()),
            updatedBy: $entry->getUpdatedBy() ? UserDto::fromEntity(user: $entry->getUpdatedBy()) : null,
            deletedBy: $entry->getDeletedBy() ? UserDto::fromEntity(user: $entry->getDeletedBy()) : null,
        );
    }
}
