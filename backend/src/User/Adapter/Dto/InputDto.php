<?php

namespace Dadinaks\User\Adapter\Dto;

use Dadinaks\Shared\Adapter\Dto\InputDtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class InputDto implements InputDtoInterface
{
    public function __construct(
        #[Assert\Sequentially(
            new Assert\NotBlank(),
        )]
        public readonly string $firstname,

        #[Assert\Sequentially(
            new Assert\NotBlank(),
        )]
        public readonly string $lastname,

        #[Assert\Sequentially(
            new Assert\NotBlank(),
        )]
        public readonly string $username,

        #[Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Length(
                min: 8,
                minMessage: 'Password must be at least {{ limit }} characters long'
            )
        ])]
        public readonly string $password,

        #[Assert\Sequentially(
            new Assert\NotBlank(),
        )]
        public readonly string $role,
    ) {}
}
