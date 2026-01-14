<?php

namespace Dadinaks\Shared\Adapter\Interface;

use Dadinaks\Shared\Adapter\Dto\OutputDtoInterface;

/**
 * Implementing this interface centralizes presentation logic,
 * facilitates consistent response feedback,
 * and ensures a clear separation between business logic (use cases) and the presentation layer.
 * This makes the code more maintainable and testable.
 * @author Dadinaks Cedrick <cedrick.henintsoa.8821@gmail.com>
 */
interface PresenterInterface
{
    public function presentSuccess(
        int $code,
        string $message,
        array|OutputDtoInterface $data
    ): array;

    public function presentError(
        int $code,
        string $message,
        ?array $data
    ): array;
}
