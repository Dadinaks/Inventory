<?php

namespace Dadinaks\Exits\Application\UseCase;

use Dadinaks\Exits\Adapter\Dto\OutputDto;
use Dadinaks\Exits\Application\Policy\ExitsPolicy;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\Product\Adapter\Dto\Shared\OutputDto as ProductDto;
use Dadinaks\Product\Domain\Repository\ProductRepositoryInterface;
use Dadinaks\User\Adapter\Dto\Shared\OutputDto as UserDto;
use Dadinaks\User\Domain\Entity\User;

final class EditExits
{
    public function __construct(
        private readonly RepositoryInterface $repository,
        private readonly ProductRepositoryInterface $productRepository,
        private ExitsPolicy $policy,
    ) {}

    public function execute(string $uid, int $quantity, User $updatedBy): OutputDto
    {
        $exits = $this->repository->findByUid(uid: $uid);

        $product = $this->productRepository->findByUid(uid: $exits->getProduct()->getUid());

        if (!$exits) {
            throw new \DomainException(
                sprintf('Exits with UID: "%s" does not exist.', $uid)
            );
        }

        $this->policy->canExits(quantity: $quantity, productUid: $exits->getProduct()->getUid());

        $product->addQuantity(quantity: $exits->getQuantity(), updatedBy: $updatedBy);
        $this->productRepository->save(entity: $product);

        $exits->update(
            quantity: $quantity,
            isDeleted: null,
            updatedBy: $updatedBy,
        );

        $product->decreaseQuantity(quantity: $quantity, updatedBy: $updatedBy);
        $this->productRepository->save(entity: $product);
        $this->repository->save(entity: $exits);

        return new OutputDto(
            uid: $exits->getUid(),
            quantity: $exits->getQuantity(),
            isDeleted: $exits->isDeleted(),
            createdAt: $exits->getCreatedAt(),
            updatedAt: $exits->getUpdatedAt(),
            deletedAt: $exits->getDeletedAt(),
            product: ProductDto::fromEntity(product: $product),
            createdBy: UserDto::fromEntity(user: $exits->getCreatedBy()),
            updatedBy: $exits->getUpdatedBy() ? UserDto::fromEntity(user: $exits->getUpdatedBy()) : null,
            deletedBy: $exits->getDeletedBy() ? UserDto::fromEntity(user: $exits->getDeletedBy()) : null,
        );
    }
}
