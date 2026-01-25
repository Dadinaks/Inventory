<?php

namespace Dadinaks\User\Adapter\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class InputLoginDto
{
    public function __construct(
        #[Assert\Sequentially(
            new Assert\NotBlank(),
        )]
        public readonly string $username,
        #[Assert\Sequentially(
            new Assert\NotBlank(),
        )]
        public readonly string $password,
    ) {}
}
