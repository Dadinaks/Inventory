<?php

namespace Dadinaks\User\Application\Policy;

use Dadinaks\User\Domain\Entity\User;

final class DeletePolicy
{
    public function canDelete(User $user): bool
    {
        return !$user->getIsDeleted();
    }

    public function canRestore(User $user): bool
    {
        return $user->getIsDeleted();
    }
}
