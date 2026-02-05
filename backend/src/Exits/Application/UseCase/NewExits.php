<?php

namespace Dadinaks\Exits\Application\UseCase;

use Dadinaks\Exits\Adapter\Dto\OutputDto;
use Dadinaks\Exits\Application\Policy\ExitsPolicy;
use Dadinaks\User\Adapter\Dto\Shared\OutputDto as UserDto;
use Dadinaks\Product\Adapter\Dto\Shared\OutputDto as ProductDto;
use Dadinaks\Exits\Domain\Entity\Exits;
use Dadinaks\Product\Domain\Repository\ProductRepositoryInterface;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\User\Domain\Entity\User;

final class NewExits
{
    public function __construct(
        private readonly RepositoryInterface $repository,
        private readonly ProductRepositoryInterface $productRepository,
        private ExitsPolicy $policy,
    ) {}

    public function execute(int $quantity, string $productUid, User $createdBy): OutputDto
    {
        $product = $this->productRepository->findByUid(uid: $productUid);

        if (!$product) {
            throw new \DomainException(
                sprintf('Product with UID: "%s" does not exist.', $productUid)
            );
        }

        $this->policy->canExits(quantity: $quantity, productUid: $productUid);

        $exits = new Exits(
            quantity: $quantity,
            product: $product,
            createdBy: $createdBy
        );

        $this->repository->save(entity: $exits);
        $product->decreaseQuantity(quantity: $quantity, updatedBy: $createdBy);
        $this->productRepository->save(entity: $product);

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
