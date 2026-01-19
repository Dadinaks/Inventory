<?php

namespace Dadinaks\Shared\Adapter\Dto;

/**
 * Marker interface for Input Data Transfer Objects (Input DTO).
 *
 * This interface is used to identify and standardize DTOs
 * that carry input data from external layers (HTTP, CLI, API, etc.)
 * to the application layer (use cases).
 *
 * Implementing this interface ensures:
 * - A clear separation between transport data and domain logic
 * - Consistent handling of input data across use cases
 * - Easier validation, transformation, and testing of incoming data
 *
 * Input DTOs should:
 * - Be immutable whenever possible
 * - Contain only primitive types or value objects
 * - Avoid any business logic
 *
 * This interface does not define any method on purpose;
 * it acts as a contract and a semantic identifier.
 *
 * @author Dadinaks Cedrick <cedrick.henintsoa.8821@gmail.com>
 */
interface InputDtoInterface {}
