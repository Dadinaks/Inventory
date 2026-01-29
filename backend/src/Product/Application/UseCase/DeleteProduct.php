<?php

namespace Dadinaks\Product\Application\UseCase;

use Dadinaks\Product\Adapter\Dto\OutputDto;
use Dadinaks\Product\Application\Policy\DeletePolicy;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\User\Adapter\Dto\Shared\OutputDto as UserDto;
use Dadinaks\User\Domain\Entity\User;

final class DeleteProduct
{
    public function __construct(
        private RepositoryInterface $repository,
        private DeletePolicy $policy
    ) {}

    public function execute(string $uid, User $deletedBy): OutputDto
    {
        $product = $this->repository->findByUid($uid);

        if (!$product) {
            throw new \DomainException(
                sprintf('Product with uid: "%s" not found.', $uid)
            );
        }

        if (!$this->policy->canDelete($product)) {
            throw new \DomainException(
                sprintf('Product "%s" is already deleted.', $product->getCode())
            );
        }

        $product->markAsDeleted($deletedBy);
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
            deletedAt: $product->getDeletedAt(),
            createdBy: UserDto::fromEntity($product->getCreatedBy()),
            updatedBy: UserDto::fromEntity($product->getUpdatedBy()),
            deletedBy: UserDto::fromEntity($product->getDeletedBy()),
        );
    }
}
