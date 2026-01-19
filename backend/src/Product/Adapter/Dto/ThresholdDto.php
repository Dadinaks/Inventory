<?php

namespace Dadinaks\Product\Adapter\Dto;

use Dadinaks\Shared\Adapter\Dto\InputDtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class ThresholdDto implements InputDtoInterface
{
    public function __construct(
        #[Assert\Sequentially(
            new Assert\Positive(message: 'Threshold should be positive.'),
        )]
        public readonly int $threshold
    ) {}
}
