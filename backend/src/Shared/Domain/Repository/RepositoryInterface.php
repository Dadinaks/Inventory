<?php

namespace Dadinaks\Shared\Domain\Repository;

/**
 * Base Repository Interface.
 *
 * This interface defines a generic contract for domain repositories.
 * It represents the abstraction between the domain layer and
 * the persistence mechanism (database, ORM, API, etc.).
 *
 * Repositories implementing this interface are responsible for:
 * - Persisting domain entities
 * - Retrieving entities based on identity or criteria
 * - Hiding infrastructure and storage details from the domain
 *
 * This interface should be extended by bounded-context-specific
 * repositories (e.g. ProductRepositoryInterface, UserRepositoryInterface).
 *
 * The domain layer depends only on this contract,
 * never on concrete persistence implementations.
 *
 * @author Dadinaks Cedrick <cedrick.henintsoa.8821@gmail.com>
 */
interface RepositoryInterface
{
    /**
     * Persists a domain entity.
     *
     * The implementation decides whether this operation
     * results in an insert or an update.
     *
     * @param object $entity Domain entity to persist
     */
    public function save(object $entity): void;

    /**
     * Finds an entity by its unique identifier.
     *
     * Returns null if no entity matches the given UID.
     *
     * @param string $uid Unique identifier (UUID)
     *
     * @return object|null
     */
    public function findByUid(string $uid): ?object;

    /**
     * Finds a single entity by given criteria.
     *
     * Criteria format and behavior depend on the implementation,
     * but must remain independent from domain logic.
     *
     * Returns null if no matching entity is found.
     *
     * @param array<string, mixed> $criteria
     *
     * @return object|null
     */
    public function findOneBy(array $criteria): ?object;

    /**
     * Returns all entities of a given type.
     *
     * This method should be used cautiously on large datasets.
     *
     * @return object[] List of domain entities
     */
    public function findAll(): array;

    /**
     * Counts entities matching given criteria.
     *
     * @param array<string, mixed> $criteria
     *
     * @return int|null Number of matching entities
     */
    public function count(array $criteria = []): ?int;
}
