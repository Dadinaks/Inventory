<?php

namespace Dadinaks\Entry\Application\Policy;

use Dadinaks\Entry\Domain\Entity\Entry;

final class DeletePolicy
{
    public function canDelete(Entry $entry): bool
    {
        return !$entry->isDeleted();
    }

    public function canRestore(Entry $entry): bool
    {
        return $entry->isDeleted();
    }
}
