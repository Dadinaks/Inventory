<?php

namespace Dadinaks\Product\Adapter\Dto;

use Dadinaks\Shared\Adapter\Dto\OutputDtoInterface;
use Dadinaks\User\Adapter\Dto\Shared\OutputDto as UserDto;

final class ProductHistoryDto implements OutputDtoInterface
{
    /**
     * @param HistoryDto[] $history
     */
    public function __construct(
        public readonly string $uid,
        public readonly string $code,
        public readonly string $name,
        public readonly int $quantity,
        public readonly int $threshold,
        public readonly bool $isDeleted,
        public readonly \DateTimeImmutable $createdAt,
        public readonly ?\DateTimeImmutable $updatedAt,
        public readonly ?\DateTimeImmutable $deletedAt,
        public readonly UserDto $createdBy,
        public readonly ?UserDto $updatedBy,
        public readonly ?UserDto $deletedBy,
        public readonly array $history,
    ) {}

    public function toArray(): array
    {
        return [
            'uid'       => $this->uid,
            'code'      => $this->code,
            'name'      => $this->name,
            'quantity'  => $this->quantity,
            'threshold' => $this->threshold,
            'isDeleted' => $this->isDeleted,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'deletedAt' => $this->deletedAt,
            'createdBy' => $this->createdBy->toArray(),
            'updatedBy' => $this->updatedBy?->toArray(),
            'deletedBy' => $this->deletedBy?->toArray(),
            'history'   => array_map(fn(HistoryDto $h) => $h->toArray(), $this->history),
        ];
    }
}
