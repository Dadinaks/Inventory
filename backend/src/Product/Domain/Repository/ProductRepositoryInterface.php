<?php

namespace Dadinaks\Product\Domain\Repository;

interface ProductRepositoryInterface
{
    public function findByUid(string $uid): ?object;

    public function count(array $criteria = []): ?int;

    public function save(object $entity): void;

    public function findHistory(string $uid): array;
}
