<?php

namespace Dadinaks\Role\Adapter\Dto\Shared;

use Dadinaks\Role\Domain\Entity\Role;
use Dadinaks\Shared\Adapter\Dto\OutputDtoInterface;

final class OutputDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $uid,
        public readonly string $label,
    ) {}

    public function toArray(): array
    {
        return [
            'uid'         => $this->uid,
            'label'       => $this->label,
        ];
    }

    public static function fromEntity(?Role $role): ?self
    {
        if (!$role) return null;

        return new self(
            uid: $role->getUid(),
            label: $role->getLabel()
        );
    }
}
