<?php

namespace Dadinaks\Role\Adapter\Dto;

use Dadinaks\Shared\Adapter\Dto\inputDtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class InputDto implements inputDtoInterface
{
    public function __construct(
        #[Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Regex(
                '/^ROLE_[A-Z_]+$/',
                'Invalid role format. It should start with "ROLE_" followed by uppercase letters, or underscores.'
            ),
        ])]
        public readonly string $role,

        #[Assert\NotBlank()]
        public readonly string $label,

        public readonly ?string $description = null,
    ) {}
}
