<?php

namespace Dadinaks\Product\Domain\Entity;

use Symfony\Component\Uid\Uuid;

/**
 * Product Domain Entity.
 *
 * This entity represents a Product within the domain layer.
 * It encapsulates the core business data and invariants related
 * to product management, independently from any framework,
 * persistence, or presentation concerns.
 *
 * Responsibilities:
 * - Hold product identity and state
 * - Guarantee domain consistency at creation time
 * - Represent the lifecycle of a product (creation, update, deletion)
 *
 * This entity:
 * - Is immutable regarding its identity (uid)
 * - Does not depend on infrastructure or application layers
 * - Can be reconstructed from persistence state via `fromState`
 *
 * @author Dadinaks Cedrick <cedrick.henintsoa.8821@gmail.com>
 */
final class Product
{
    private string $uid;

    private string $code;

    private string $name;

    private int $quantity;

    private int $threshold;

    private bool $isDeleted = false;

    private \DateTimeImmutable $createdAt;

    private ?\DateTimeImmutable $updatedAt = null;

    private ?\DateTimeImmutable $deletedAt = null;

    /**
     * Creates a new Product instance.
     *
     * This constructor is intended for **new products only**.
     * Default values are applied to ensure a valid initial state.
     *
     * @param string $code Business product code
     * @param string $name Product name
     */
    public function __construct(string $code, string $name)
    {
        $this->uid        = Uuid::v7()->toString();
        $this->code       = $code;
        $this->name       = $name;
        $this->quantity   = 0;
        $this->threshold  = 0;
        $this->createdAt  = new \DateTimeImmutable();
    }

    /**
     * Reconstitutes a Product from a persisted state.
     *
     * This factory method is used by repositories to rebuild
     * the domain entity from database or storage data,
     * without triggering domain side effects.
     *
     * @param array{
     *   uid: string,
     *   code: string,
     *   name: string,
     *   quantity: int,
     *   threshold: int,
     *   isDeleted: bool,
     *   createdAt: \DateTimeImmutable,
     *   updatedAt: ?\DateTimeImmutable,
     *   deletedAt: ?\DateTimeImmutable
     * } $state
     */
    public static function fromState(array $state): self
    {
        $product = new self($state['code'], $state['name']);

        $product->uid        = $state['uid'];
        $product->quantity   = $state['quantity'];
        $product->threshold  = $state['threshold'];
        $product->isDeleted  = $state['isDeleted'];
        $product->createdAt  = $state['createdAt'];
        $product->updatedAt  = $state['updatedAt'];
        $product->deletedAt  = $state['deletedAt'];

        return $product;
    }

    public function update(?int $threshold, ?bool $isDeleted): void
    {
        if ($threshold !== null) {
            if ($this->threshold === $threshold) {
                throw new \DomainException(sprintf('Threshold is already set to %d.', $threshold));
            }
            $this->threshold = $threshold;
        }

        if ($isDeleted !== null && $isDeleted !== $this->isDeleted) {
            $this->isDeleted = $isDeleted;
            $this->deletedAt = $isDeleted ? new \DateTimeImmutable() : null;
        }

        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markAsDeleted(): void
    {
        if (!$this->isDeleted) {
            $this->isDeleted = true;
            $this->deletedAt = new \DateTimeImmutable();
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getUid(): string
    {
        return $this->uid;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getThreshold(): int
    {
        return $this->threshold;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }
}
