<?php

namespace Dadinaks\Product\Adapter\Dto;

use Dadinaks\Shared\Adapter\Dto\OutputDtoInterface;
use Dadinaks\User\Adapter\Dto\Shared\OutputDto as UserDto;

final class HistoryDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $uid,
        public readonly int $quantity,
        public readonly \DateTimeImmutable $date,
        public readonly string $type,
        public readonly UserDto $user
    ) {}

    public function toArray(): array
    {
        return [
            'uid'      => $this->uid,
            'quantity' => $this->quantity,
            'date'     => $this->date,
            'type'     => $this->type,
            'user'     => $this->user->toArray(),
        ];
    }
}
