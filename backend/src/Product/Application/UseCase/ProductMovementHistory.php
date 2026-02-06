<?php

namespace Dadinaks\Product\Application\UseCase;

use Dadinaks\Product\Adapter\Dto\ProductHistoryDto;
use Dadinaks\Product\Adapter\Dto\HistoryDto;
use Dadinaks\User\Adapter\Dto\Shared\OutputDto as UserDto;
use Dadinaks\Product\Domain\Repository\ProductRepositoryInterface;
use Dadinaks\User\Domain\Repository\UserRepositoryInterface;

final class ProductMovementHistory
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function execute(string $uid): ProductHistoryDto
    {
        $product = $this->productRepository->findByUid(uid: $uid);

        if (!$product) {
            throw new \DomainException(
                sprintf('Product with uid: "%s" not found.', $uid)
            );
        }

        $history = $this->productRepository->findHistory(uid: $uid);

        $historyDtos = array_map(function (array $item) {
            $user = null;

            if (!empty($item['user'])) {
                $userEntity = $this->userRepository->findByUid(uid: $item['user']);
                $user = $userEntity ? UserDto::fromEntity(user: $userEntity) : null;
            }

            return new HistoryDto(
                uid: $item['uid'],
                quantity: $item['quantity'],
                date: $item['date'],
                type: $item['type'],
                user: $user
            );
        }, $history);

        return new ProductHistoryDto(
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
            updatedBy: $product->getUpdatedBy() ? UserDto::fromEntity(user: $product->getUpdatedBy()) : null,
            deletedBy: $product->getDeletedBy() ? UserDto::fromEntity(user: $product->getDeletedBy()) : null,
            history: $historyDtos,
        );
    }
}
