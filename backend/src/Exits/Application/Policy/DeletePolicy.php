<?php

namespace Dadinaks\Exits\Application\Policy;

use Dadinaks\Exits\Domain\Entity\Exits;

final class DeletePolicy
{
    public function canDelete(Exits $exits): bool
    {
        return !$exits->isDeleted();
    }

    public function canRestore(Exits $exits): bool
    {
        return $exits->isDeleted();
    }
}
