<?php

namespace Dadinaks\Entry\Infrastructure\Persistence\Doctrine\Entity;

use Dadinaks\Entry\Domain\Entity\Entry;
use Dadinaks\Product\Infrastructure\Persistence\Doctrine\Entity\ProductOrm as Product;
use Dadinaks\User\Infrastructure\Persistence\Doctrine\Entity\UserOrm as User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table('entries')]
final class EntryOrm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'uuid', unique: true)]
    private string $uid;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    #[ORM\Column(type: 'boolean')]
    private bool $isDeleted = false;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Product $product;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $createdBy;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $updatedBy = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $deletedBy = null;

    public static function fromDomain(Entry $entry, User $createdBy): self
    {
        $orm = new self();
        $orm->uid           = $entry->getUid();
        $orm->quantity      = $entry->getQuantity();
        $orm->isDeleted    = $entry->isDeleted();
        $orm->createdAt    = $entry->getCreatedAt();
        $orm->updatedAt    = $entry->getUpdatedAt();
        $orm->deletedAt    = $entry->getDeletedAt();
        $orm->product      = Product::fromDomain($entry->getProduct(), $entry->getProduct()->getCreatedBy());
        $orm->createdBy    = $createdBy;
        $orm->updatedBy    = $entry->getUpdatedBy();
        $orm->deletedBy    = $entry->getDeletedBy();

        return $orm;
    }
}
