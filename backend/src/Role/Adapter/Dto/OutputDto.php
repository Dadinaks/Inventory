<?php

namespace Dadinaks\Role\Adapter\Dto;

use Dadinaks\Shared\Adapter\Dto\OutputDtoInterface;

final class OutputDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $uid,
        public readonly string $role,
        public readonly string $label,
        public readonly ?string $description,
    ) {}

    public function toArray(): array
    {
        return [
            'uid'         => $this->uid,
            'role'        => $this->role,
            'label'       => $this->label,
            'description' => $this->description,
        ];
    }
}
