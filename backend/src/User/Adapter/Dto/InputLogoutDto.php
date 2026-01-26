<?php

namespace Dadinaks\User\Adapter\Dto;

use Dadinaks\Shared\Adapter\Dto\InputDtoInterface;

final class InputLogoutDto implements InputDtoInterface
{
    public function __construct(
        public readonly string $username,
        public readonly string $token
    ) {}
}
