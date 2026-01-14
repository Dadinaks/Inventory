<?php

namespace Dadinaks\Product\Adapter\Dto;

use Dadinaks\Shared\Adapter\Dto\OutputDtoInterface;

final class OutputDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $uid,
        public readonly string $code,
        public readonly string $name,
        public readonly int $quantity,
        public readonly int $threshold,
        public readonly bool $isDeleted,
        public readonly \DateTimeImmutable $createdAt,
        public readonly ?\DateTimeImmutable $updatedAt,
        public readonly ?\DateTimeImmutable $deletedAt
    ) {}

    public function toArray(): array
    {
        return [
            'uid'        => $this->uid,
            'code'       => $this->code,
            'name'       => $this->name,
            'quantity'   => $this->quantity,
            'threshold'  => $this->threshold,
            'isDeleted'  => $this->isDeleted,
            'createdAt'  => $this->createdAt,
            'updatedAt'  => $this->updatedAt,
            'deletedAt'  => $this->deletedAt
        ];
    }
}
