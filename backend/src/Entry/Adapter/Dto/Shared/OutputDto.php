<?php

namespace Dadinaks\Entry\Adapter\Dto\Shared;

use Dadinaks\Entry\Domain\Entity\Entry;
use Dadinaks\Shared\Adapter\Dto\OutputDtoInterface;
use Dadinaks\Product\Adapter\Dto\Shared\OutputDto as ProductDto;
use Dadinaks\User\Adapter\Dto\Shared\OutputDto as UserDto;

final class OutputDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $uid,
        public readonly int $quantity,
        public readonly ProductDto $product,
        public readonly \DateTimeImmutable $createdAt,
        public readonly UserDto $createdBy
    ) {}

    public function toArray(): array
    {
        return [
            'uid'        => $this->uid,
            'quantity'   => $this->quantity,
            'product'    => $this->product->toArray(),
            'createdAt'  => $this->createdAt,
            'createdBy'   => $this->createdBy->toArray(),
        ];
    }

    public static function fromEntity(?Entry $entry): ?self
    {
        if (!$entry) return null;

        return new self(
            uid: $entry->getUid(),
            quantity: $entry->getQuantity(),
            product: ProductDto::fromEntity(product: $entry->getProduct()),
            createdAt: $entry->getCreatedAt(),
            createdBy: UserDto::fromEntity(user: $entry->getCreatedBy())
        );
    }
}
