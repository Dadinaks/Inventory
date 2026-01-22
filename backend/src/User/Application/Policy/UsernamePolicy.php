<?php

namespace Dadinaks\User\Application\Policy;

use Dadinaks\User\Domain\Repository\UserRepositoryInterface;

final class UsernamePolicy
{
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    public function isAlreadyUsed(string $username): bool
    {
        return $this->repository->count(['username' => $username]) > 0;
    }
}
