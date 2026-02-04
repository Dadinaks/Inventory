<?php

namespace Dadinaks\Entry\Application\UseCase;

use Dadinaks\Entry\Adapter\Dto\OutputDto;
use Dadinaks\Entry\Application\Policy\DeletePolicy;
use Dadinaks\Product\Adapter\Dto\Shared\OutputDto as ProductDto;
use Dadinaks\Product\Domain\Repository\ProductRepositoryInterface;
use Dadinaks\User\Adapter\Dto\Shared\OutputDto as UserDto;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\User\Domain\Entity\User;

final class DeleteEntry
{
    public function __construct(
        private readonly RepositoryInterface $repository,
        private readonly ProductRepositoryInterface $productRepository,
        private DeletePolicy $policy
    ) {}

    public function execute(string $uid, User $deletedBy): OutputDto
    {
        $entry = $this->repository->findByUid($uid);

        if (!$entry) {
            throw new \DomainException(
                sprintf('Entry with UID: "%s" does not exist.', $uid)
            );
        }

        if (!$this->policy->canDelete($entry)) {
            throw new \DomainException(
                sprintf('Entry with uid "%s" is already deleted.', $uid)
            );
        }

        $product = $this->productRepository->findByUid($entry->getProduct()->getUid());
        $product->decreaseQuantity($entry->getQuantity(), $deletedBy);
        $this->productRepository->save($product);

        $entry->markAsDeleted($deletedBy);
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
