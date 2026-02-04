<?php

namespace Dadinaks\Entry\Application\UseCase;

use Dadinaks\Entry\Adapter\Dto\OutputDto;
use Dadinaks\User\Adapter\Dto\Shared\OutputDto as UserDto;
use Dadinaks\Product\Adapter\Dto\Shared\OutputDto as ProductDto;
use Dadinaks\Entry\Domain\Entity\Entry;
use Dadinaks\Product\Domain\Repository\ProductRepositoryInterface;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\User\Domain\Entity\User;

final class NewEntry
{
    public function __construct(
        private readonly RepositoryInterface $repository,
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    public function execute(int $quantity, string $productUid, User $createdBy): OutputDto
    {
        $product = $this->productRepository->findByUid(uid: $productUid);

        if (!$product) {
            throw new \DomainException(
                sprintf('Product with UID: "%s" does not exist.', $productUid)
            );
        }

        if ($quantity <= 0) {
            throw new \DomainException('Quantity must be greater than zero.');
        }

        $entry = new Entry(
            quantity: $quantity,
            product: $product,
            createdBy: $createdBy
        );

        $this->repository->save(entity: $entry);
        $product->addQuantity(quantity: $quantity, updatedBy: $createdBy);
        $this->productRepository->save(entity: $product);

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
