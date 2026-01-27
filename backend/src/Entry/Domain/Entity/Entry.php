<?php

namespace Dadinaks\Entry\Domain\Entity;

use Dadinaks\Product\Domain\Entity\Product;

final class Entry
{
    private string $uid;

    private int $quantity;

    private \DateTimeImmutable $createdAt;

    private ?\DateTimeImmutable $updatedAt = null;

    private Product $product;
}
