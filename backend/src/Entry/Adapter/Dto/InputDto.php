<?php

namespace Dadinaks\Entry\Adapter\Dto;

use Dadinaks\Shared\Adapter\Dto\InputDtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class InputDto implements InputDtoInterface
{
    public function __construct(
        #[Assert\Sequentially([
            new Assert\NotBlank(message: 'The quantity field is required.'),
            new Assert\Positive(message: 'The quantity must be a positive number.'),
        ])]
        public readonly int $quantity,
        public readonly string $productUid
    ) {}
}
