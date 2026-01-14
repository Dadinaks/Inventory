<?php

namespace Dadinaks\Product\Adapter\Dto;

use Dadinaks\Shared\Adapter\Dto\inputDtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class InputDto implements inputDtoInterface
{
    public function __construct(
        #[Assert\Sequentially(
            new Assert\NotBlank(),
        )]
        public readonly string $name
    ) {}
}
