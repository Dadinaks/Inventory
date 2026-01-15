<?php

namespace Dadinaks\Product\Application\UseCase;

use Dadinaks\Product\Adapter\Dto\OutputDto;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;

final class DeleteProduct
{
    public function __construct(
        private RepositoryInterface $repository
    ) {}

    public function execute(string $uid): OutputDto
    {
        $product = $this->repository->findByUid($uid);

        if (!$product) {
            throw new \DomainException(
                sprintf('Product with uid: "%s" not found.', $uid)
            );
        }

        $product->markAsDeleted();
        $this->repository->save($product);

        return new OutputDto(
            uid: $product->getUid(),
            code: $product->getCode(),
            name: $product->getName(),
            quantity: $product->getQuantity(),
            threshold: $product->getThreshold(),
            isDeleted: $product->isDeleted(),
            createdAt: $product->getCreatedAt(),
            updatedAt: $product->getUpdatedAt(),
            deletedAt: $product->getDeletedAt()
        );
    }
}
