<?php

namespace Dadinaks\Product\Application\UseCase;

use Dadinaks\Product\Adapter\Dto\OutputDto;
use Dadinaks\Product\Domain\Entity\Product;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\User\Domain\Entity\User;
use Dadinaks\User\Adapter\Dto\Shared\OutputDto as UserDto;


final class CreateProduct
{
    public function __construct(
        private RepositoryInterface $repository
    ) {}

    public function execute(string $name, User $createdBy): OutputDto
    {
        if ($this->repository->findOneBy(['name' => $name])) {
            throw new \DomainException(
                sprintf('Product with name: "%s" already exists.', $name)
            );
        }

        $product = new Product(
            code: $this->generateCode(),
            name: $name,
            createdBy: $createdBy
        );
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

    private function generateCode()
    {
        $nb = $this->repository->count([]);
        return 'P' . str_pad((string)(($nb ?? 0) + 1), 5, '0', STR_PAD_LEFT);
    }
}
