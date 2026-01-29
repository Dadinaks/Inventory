<?php

namespace Dadinaks\Entry\Domain\Entity;

use Dadinaks\Product\Domain\Entity\Product;
use Dadinaks\User\Domain\Entity\User;
use Symfony\Component\Uid\Uuid;

final class Entry
{
    private string $uid;

    private int $quantity;

    private bool $isDeleted = false;

    private \DateTimeImmutable $createdAt;

    private ?\DateTimeImmutable $updatedAt = null;

    private ?\DateTimeImmutable $deletedAt = null;

    private Product $product;

    private User $createdBy;

    private ?User $updatedBy = null;

    private ?User $deletedBy = null;

    public function __construct(int $quantity, Product $product, User $createdBy)
    {
        $this->uid = Uuid::v7()->toString();
        $this->quantity = $quantity;
        $this->createdAt = new \DateTimeImmutable();
        $this->product = $product;
        $this->createdBy = $createdBy;
    }

    public static function fromState(array $state): self
    {
        $entry = new self($state['quantity'], $state['product'], $state['createdBy']);

        $entry->uid        = $state['uid'];
        $entry->isDeleted  = $state['isDeleted'];
        $entry->createdAt  = $state['createdAt'];
        $entry->updatedAt  = $state['updatedAt'];
        $entry->deletedAt  = $state['deletedAt'];
        $entry->updatedBy  = $state['updatedBy'];
        $entry->deletedBy  = $state['deletedBy'];

        return $entry;
    }

    public function update(int $quantity, ?bool $isDeleted, ?User $updatedBy): void
    {
        if ($updatedBy !== null) {
            $isUpdate = false;

            if ($this->quantity !== $quantity) {
                $this->quantity = $quantity;
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
            throw new \InvalidArgumentException('Need user for updating entry.');
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


    public function getUid(): string
    {
        return $this->uid;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
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

    public function getProduct(): Product
    {
        return $this->product;
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
