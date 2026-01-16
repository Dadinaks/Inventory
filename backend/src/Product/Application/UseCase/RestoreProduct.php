<?php

namespace Dadinaks\Product\Application\UseCase;

use Dadinaks\Product\Adapter\Dto\OutputDto;
use Dadinaks\Product\Application\Policy\DeletePolicy;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;

final class RestoreProduct
{
    public function __construct(
        private RepositoryInterface $repository,
        private DeletePolicy $policy
    ) {}

    public function execute(string $uid): OutputDto
    {
        $product = $this->repository->findByUid($uid);

        if (!$this->policy->canRestore($product)) {
            throw new \DomainException(
                sprintf('Product "%s" is not deleted.', $product->getCode())
            );
        }

        $product->update(null, false);
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
