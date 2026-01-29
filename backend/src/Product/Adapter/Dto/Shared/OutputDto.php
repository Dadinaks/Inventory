<?php

namespace Dadinaks\Product\Adapter\Dto\Shared;

use Dadinaks\Product\Domain\Entity\Product;
use Dadinaks\Shared\Adapter\Dto\OutputDtoInterface;

final class OutputDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $uid,
        public readonly string $code,
        public readonly string $name,
        public readonly int $quantity,
        public readonly int $threshold
    ) {}

    public function toArray(): array
    {
        return [
            'uid'        => $this->uid,
            'code'       => $this->code,
            'name'       => $this->name,
            'quantity'   => $this->quantity,
            'threshold'  => $this->threshold
        ];
    }

    public static function fromEntity(?Product $product): ?self
    {
        if (!$product) return null;

        return new self(
            uid: $product->getUid(),
            code: $product->getCode(),
            name: $product->getName(),
            quantity: $product->getQuantity(),
            threshold: $product->getThreshold()
        );
    }
}
