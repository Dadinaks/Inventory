<?php

namespace Dadinaks\Exits\Application\Policy;

use Dadinaks\Product\Domain\Repository\ProductRepositoryInterface;

final class ExitsPolicy
{
    public function __construct(
        private readonly ProductRepositoryInterface $repository,
    ) {}

    public function canExits(int $quantity, string $productUid): void
    {
        $product = $this->repository->findByUid(uid: $productUid);

        if ($quantity <= 0) {
            throw new \DomainException('Quantity must be greater than zero.');
        } elseif ($quantity > $product->getQuantity()) {
            throw new \DomainException(
                sprintf(
                    'Quantity must not exceed %s.',
                    $product->getQuantity()
                )
            );
        }
    }
}
