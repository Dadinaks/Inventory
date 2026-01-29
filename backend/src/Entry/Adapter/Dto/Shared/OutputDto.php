<?php

namespace Dadinaks\Entry\Adapter\Dto\Shared;

use Dadinaks\Entry\Domain\Entity\Entry;
use Dadinaks\Shared\Adapter\Dto\OutputDtoInterface;
use Dadinaks\Product\Adapter\Dto\Shared\OutputDto as ProductDto;

final class OutputDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $uid,
        public readonly int $quantity,
        public readonly ProductDto $product,
        public readonly \DateTimeImmutable $createdAt
    ) {}

    public function toArray(): array
    {
        return [
            'uid'        => $this->uid,
            'quantity'   => $this->quantity,
            'product'    => $this->product->toArray(),
            'createdAt'  => $this->createdAt
        ];
    }

    public static function fromEntity(?Entry $entry): ?self
    {
        if (!$entry) return null;

        return new self(
            uid: $entry->getUid(),
            quantity: $entry->getQuantity(),
            product: ProductDto::fromEntity($entry->getProduct()),
            createdAt: $entry->getCreatedAt()
        );
    }
}
