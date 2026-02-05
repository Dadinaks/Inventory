<?php

namespace Dadinaks\Exits\Adapter\Dto\Shared;

use Dadinaks\Exits\Domain\Entity\Exits;
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

    public static function fromEntity(?Exits $exits): ?self
    {
        if (!$exits) return null;

        return new self(
            uid: $exits->getUid(),
            quantity: $exits->getQuantity(),
            product: ProductDto::fromEntity($exits->getProduct()),
            createdAt: $exits->getCreatedAt(),
            createdBy: UserDto::fromEntity($exits->getCreatedBy())
        );
    }
}
