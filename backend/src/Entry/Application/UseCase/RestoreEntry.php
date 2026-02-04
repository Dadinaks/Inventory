<?php

namespace Dadinaks\Entry\Application\UseCase;

use Dadinaks\Entry\Adapter\Dto\OutputDto;
use Dadinaks\Product\Adapter\Dto\Shared\OutputDto as ProductDto;
use Dadinaks\Entry\Application\Policy\DeletePolicy;
use Dadinaks\User\Adapter\Dto\Shared\OutputDto as UserDto;
use Dadinaks\Product\Domain\Repository\ProductRepositoryInterface;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\User\Domain\Entity\User;

final class RestoreEntry
{
    public function __construct(
        private readonly RepositoryInterface $repository,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly DeletePolicy $policy
    ) {}

    public function execute(string $uid, User $updatedBy): OutputDto
    {
        $entry = $this->repository->findByUid(uid: $uid);

        if (!$entry) {
            throw new \DomainException(
                sprintf('Entry with UID: "%s" does not exist.', $uid)
            );
        }

        if (!$this->policy->canRestore(entry: $entry)) {
            throw new \DomainException(
                sprintf('Entry with uid "%s" is not deleted.', $uid)
            );
        }

        $product = $this->productRepository->findByUid(uid: $entry->getProduct()->getUid());
        $product->addQuantity(quantity: $entry->getQuantity(), updatedBy: $updatedBy);
        $this->productRepository->save(entity: $product);

        $entry->update(quantity: $entry->getQuantity(), isDeleted: false, updatedBy: $updatedBy);
        $this->repository->save(entity: $entry);


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
