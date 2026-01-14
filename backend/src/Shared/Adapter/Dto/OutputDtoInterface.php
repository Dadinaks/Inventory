<?php

namespace Dadinaks\Shared\Adapter\Dto;


/**
 * Marker interface for Output Data Transfer Objects (Output DTO).
 *
 * This interface is used to identify and standardize DTOs
 * that carry data from the application layer (use cases)
 * to external layers (API, UI, CLI, etc.).
 *
 * Implementing this interface ensures:
 * - A clear and explicit contract for data returned by use cases
 * - Consistent formatting and handling of output data
 * - A strict separation between application results and presentation logic
 *
 * Output DTOs should:
 * - Represent read-only data
 * - Contain no business logic
 * - Be independent from framework-specific implementations
 * - Be easily serializable (JSON, XML, etc.)
 *
 * This interface intentionally defines no methods.
 * It acts as a semantic marker to improve readability,
 * enforce architectural boundaries, and ease future extensions.
 *
 * @author Dadinaks Cedrick <cedrick.henintsoa.8821@gmail.com>
 */
interface OutputDtoInterface
{
    public function toArray(): array;
}
