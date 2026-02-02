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
        $product = $this->productRepository->findByUid($productUid);

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

        $this->repository->save($entry);
        $product->addQuantity($quantity, $createdBy);
        $this->productRepository->save($product);

        return new OutputDto(
            uid: $entry->getUid(),
            quantity: $entry->getQuantity(),
            isDeleted: $entry->isDeleted(),
            createdAt: $entry->getCreatedAt(),
            updatedAt: $entry->getUpdatedAt(),
            deletedAt: $entry->getDeletedAt(),
            product: ProductDto::fromEntity($entry->getProduct()),
            createdBy: UserDto::fromEntity($entry->getCreatedBy()),
            updatedBy: null,
            deletedBy: null
        );
    }
}
