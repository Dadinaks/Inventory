<?php

namespace Dadinaks\User\Application\Policy;

use Dadinaks\User\Domain\Entity\User;

final class ActivePolicy
{
    public function canEnable(User $user): bool
    {
        return $user->getIsActive();
    }

    public function canDisable(User $user): bool
    {
        return !$user->getIsActive();
    }
}
