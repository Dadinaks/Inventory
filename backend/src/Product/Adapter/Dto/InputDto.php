<?php

namespace Dadinaks\Product\Adapter\Dto;

use Dadinaks\Shared\Adapter\Dto\inputDtoInterface;

final class InputDto implements inputDtoInterface
{
    public function __construct(
        public readonly string $code,
        public readonly float $name
    ) {}
}
