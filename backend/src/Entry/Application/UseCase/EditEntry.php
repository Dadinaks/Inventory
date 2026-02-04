<?php

namespace Dadinaks\Entry\Application\UseCase;

use Dadinaks\Entry\Adapter\Dto\OutputDto;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\Product\Adapter\Dto\Shared\OutputDto as ProductDto;
use Dadinaks\Product\Domain\Repository\ProductRepositoryInterface;
use Dadinaks\User\Adapter\Dto\Shared\OutputDto as UserDto;
use Dadinaks\User\Domain\Entity\User;

final class EditEntry
{
    public function __construct(
        private readonly RepositoryInterface $repository,
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    public function execute(string $uid, int $quantity, User $updatedBy): OutputDto
    {
        $entry = $this->repository->findByUid($uid);

        $product = $this->productRepository->findByUid($entry->getProduct()->getUid());

        if (!$entry) {
            throw new \DomainException(
                sprintf('Entry with UID: "%s" does not exist.', $uid)
            );
        }

        if ($quantity <= 0) {
            throw new \DomainException('Quantity must be greater than zero.');
        }

        $product->decreaseQuantity($entry->getQuantity(), $updatedBy);
        $this->productRepository->save($product);

        $entry->update(
            quantity: $quantity,
            isDeleted: null,
            updatedBy: $updatedBy,
        );

        $product->addQuantity($quantity, $updatedBy);
        $this->productRepository->save($product);
        $this->repository->save($entry);

        return new OutputDto(
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
        );
    }
}
