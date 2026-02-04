<?php

namespace Dadinaks\Product\Application\UseCase;

use Dadinaks\Product\Adapter\Dto\OutputDto;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\User\Adapter\Dto\Shared\OutputDto as UserDto;
use Dadinaks\User\Domain\Entity\User;

final class AddThreshold
{
    public function __construct(
        private RepositoryInterface $repository
    ) {}

    public function execute(string $uid, int $threshold, User $updatedBy): OutputDto
    {
        $product = $this->repository->findByUid(uid: $uid);

        if (!$product) {
            throw new \DomainException(
                sprintf('Product with uid: "%s" not found.', $uid)
            );
        }

        $product->update(threshold: $threshold, isDeleted: null, updatedBy: $updatedBy);
        $this->repository->save(entity: $product);

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
            createdBy: UserDto::fromEntity(user: $product->getCreatedBy()),
            updatedBy: UserDto::fromEntity(user: $product->getUpdatedBy()),
            deletedBy: UserDto::fromEntity(user: $product->getDeletedBy()),
        );
    }
}
