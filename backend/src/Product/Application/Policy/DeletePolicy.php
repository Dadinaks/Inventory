<?php

namespace Dadinaks\Product\Application\Policy;

use Dadinaks\Product\Domain\Entity\Product;

final class DeletePolicy
{
    public function canDelete(Product $product): bool
    {
        return !$product->isDeleted();
    }
}
