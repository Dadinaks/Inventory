<?php

namespace Dadinaks\Product\Application\Policy;

use Dadinaks\Product\Domain\Entity\Product;

final class ThresholdPolicy
{

    public function isBelowThreshold(Product $product): bool
    {
        return $product->getQuantity() < $product->getThreshold();
    }
}
