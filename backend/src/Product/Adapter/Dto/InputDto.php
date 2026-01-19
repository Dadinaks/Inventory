<?php

namespace Dadinaks\Product\Adapter\Dto;

use Dadinaks\Shared\Adapter\Dto\InputDtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class InputDto implements InputDtoInterface
{
    public function __construct(
        #[Assert\Sequentially(
            new Assert\NotBlank(),
        )]
        public readonly string $name
    ) {}
}
