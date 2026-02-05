<?php

namespace Dadinaks\Exits\Application\UseCase;

use Dadinaks\Exits\Adapter\Dto\OutputDto;
use Dadinaks\Exits\Application\Policy\DeletePolicy;
use Dadinaks\Product\Adapter\Dto\Shared\OutputDto as ProductDto;
use Dadinaks\Product\Domain\Repository\ProductRepositoryInterface;
use Dadinaks\User\Adapter\Dto\Shared\OutputDto as UserDto;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\User\Domain\Entity\User;

final class DeleteExits
{
    public function __construct(
        private readonly RepositoryInterface $repository,
        private readonly ProductRepositoryInterface $productRepository,
        private DeletePolicy $policy
    ) {}

    public function execute(string $uid, User $deletedBy): OutputDto
    {
        $exits = $this->repository->findByUid($uid);

        if (!$exits) {
            throw new \DomainException(
                sprintf('Exits with UID: "%s" does not exist.', $uid)
            );
        }

        if (!$this->policy->canDelete($exits)) {
            throw new \DomainException(
                sprintf('Exits with uid "%s" is already deleted.', $uid)
            );
        }

        $product = $this->productRepository->findByUid(uid: $exits->getProduct()->getUid());
        $product->addQuantity(quantity: $exits->getQuantity(), updatedBy: $deletedBy);
        $this->productRepository->save(entity: $product);

        $exits->markAsDeleted(deletedBy: $deletedBy);
        $this->repository->save(entity: $exits);


        return new OutputDto(
            uid: $exits->getUid(),
            quantity: $exits->getQuantity(),
            isDeleted: $exits->isDeleted(),
            createdAt: $exits->getCreatedAt(),
            updatedAt: $exits->getUpdatedAt(),
            deletedAt: $exits->getDeletedAt(),
            product: ProductDto::fromEntity(product: $exits->getProduct()),
            createdBy: UserDto::fromEntity(user: $exits->getCreatedBy()),
            updatedBy: $exits->getUpdatedBy() ? UserDto::fromEntity(user: $exits->getUpdatedBy()) : null,
            deletedBy: $exits->getDeletedBy() ? UserDto::fromEntity(user: $exits->getDeletedBy()) : null,
        );
    }
}
