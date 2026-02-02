<?php

namespace Dadinaks\Entry\Adapter\Dto;

use Dadinaks\Shared\Adapter\Dto\OutputDtoInterface;
use Dadinaks\Product\Adapter\Dto\Shared\OutputDto as ProductDto;
use Dadinaks\User\Adapter\Dto\Shared\OutputDto as UserDto;

final class OutputDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $uid,
        public readonly int $quantity,
        public readonly ProductDto $product,
        public readonly UserDto $createdBy,
        public readonly ?UserDto $updatedBy,
        public readonly ?UserDto $deletedBy,
        public readonly bool $isDeleted,
        public readonly \DateTimeImmutable $createdAt,
        public readonly ?\DateTimeImmutable $updatedAt,
        public readonly ?\DateTimeImmutable $deletedAt
    ) {}

    public function toArray(): array
    {
        return [
            'uid'        => $this->uid,
            'quantity'   => $this->quantity,
            'product'    => $this->product->toArray(),
            'createdBy'  => $this->createdBy->toArray(),
            'updatedBy'  => $this->updatedBy?->toArray(),
            'deletedBy'  => $this->deletedBy?->toArray(),
            'isDeleted'  => $this->isDeleted,
            'createdAt'  => $this->createdAt,
            'updatedAt'  => $this->updatedAt,
            'deletedAt'  => $this->deletedAt,
        ];
    }
}
