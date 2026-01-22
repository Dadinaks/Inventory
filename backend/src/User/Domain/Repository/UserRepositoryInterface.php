<?php

namespace Dadinaks\User\Domain\Repository;

interface UserRepositoryInterface
{
    public function findByUid(string $uid): ?object;

    public function count(array $criteria = []): ?int;
}
