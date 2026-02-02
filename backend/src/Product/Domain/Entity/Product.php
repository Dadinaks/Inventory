<?php

namespace Dadinaks\Product\Domain\Entity;

use Dadinaks\User\Domain\Entity\User;
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

    private User $createdBy;

    private ?User $updatedBy = null;

    private ?User $deletedBy = null;

    /**
     * Creates a new Product instance.
     *
     * This constructor is intended for **new products only**.
     * Default values are applied to ensure a valid initial state.
     *
     * @param string $code Business product code
     * @param string $name Product name
     */
    public function __construct(string $code, string $name, User $createdBy)
    {
        $this->uid        = Uuid::v7()->toString();
        $this->code       = $code;
        $this->name       = $name;
        $this->quantity   = 0;
        $this->threshold  = 0;
        $this->createdAt  = new \DateTimeImmutable();
        $this->createdBy  = $createdBy;
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
        $product = new self($state['code'], $state['name'], $state['createdBy']);

        $product->uid        = $state['uid'];
        $product->quantity   = $state['quantity'];
        $product->threshold  = $state['threshold'];
        $product->isDeleted  = $state['isDeleted'];
        $product->createdAt  = $state['createdAt'];
        $product->updatedAt  = $state['updatedAt'];
        $product->deletedAt  = $state['deletedAt'];
        $product->updatedBy  = $state['updatedBy'];
        $product->deletedBy  = $state['deletedBy'];

        return $product;
    }

    public function update(?int $threshold, ?bool $isDeleted, ?User $updatedBy): void
    {
        if ($updatedBy !== null) {
            $isUpdate = false;

            if ($threshold !== null) {
                if ($this->threshold === $threshold) {
                    throw new \DomainException(sprintf('Threshold is already set to %d.', $threshold));
                }
                $this->threshold = $threshold;
                $isUpdate = true;
            }

            if ($isDeleted !== null && $isDeleted !== $this->isDeleted) {
                $this->isDeleted = $isDeleted;
                $this->deletedAt = null;
                $this->deletedBy = null;
                $isUpdate = true;
            }

            if ($isUpdate) {
                $this->updatedBy = $updatedBy;
                $this->updatedAt = new \DateTimeImmutable();
            }
        } else {
            throw new \InvalidArgumentException('Need user for updating product.');
        }
    }

    public function markAsDeleted(User $deletedBy): void
    {
        if (!$this->isDeleted) {
            $this->isDeleted = true;
            $this->deletedAt = new \DateTimeImmutable();
            $this->updatedAt = new \DateTimeImmutable();
            $this->deletedBy = $deletedBy;
        }
    }

    public function addQuantity(int $quantity, User $updatedBy): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Quantity to add must be positive.');
        }

        $this->quantity += $quantity;
        $this->updatedAt = new \DateTimeImmutable();
        $this->updatedBy = $updatedBy;
    }

    public function decreaseQuantity(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Quantity to decrease must be positive.');
        }

        if ($this->quantity < $quantity) {
            throw new \DomainException('Insufficient product quantity in stock. The available quantity is: ' . $this->quantity);
        }

        $this->quantity -= $quantity;
        $this->updatedAt = new \DateTimeImmutable();
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

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function getDeletedBy(): ?User
    {
        return $this->deletedBy;
    }
}
