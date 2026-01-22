<?php

namespace Dadinaks\Role\Domain\Repository;

interface RoleRepositoryInterface 
{
    public function findByUid(string $uid): ?object;

    public function count(array $criteria = []): ?int;
}