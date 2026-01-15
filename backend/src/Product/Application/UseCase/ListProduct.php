<?php

namespace Dadinaks\Product\Application\UseCase;

use Dadinaks\Product\Adapter\Dto\OutputDto;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;

final class ListProduct
{
    public function __construct(
        private readonly RepositoryInterface $repository
    ) {}

    public function execute(): array
    {
        $product = $this->repository->findAll();

        if (empty($product)) {
            throw new \DomainException('No products found in the system.');
        }

        return array_map(
            fn($product) => new OutputDto(
                uid: $product->getUid(),
                code: $product->getCode(),
                name: $product->getName(),
                quantity: $product->getQuantity(),
                threshold: $product->getThreshold(),
                isDeleted: $product->isDeleted(),
                createdAt: $product->getCreatedAt(),
                updatedAt: $product->getUpdatedAt(),
                deletedAt: $product->getDeletedAt()
            ),
            $product
        );
    }
}
