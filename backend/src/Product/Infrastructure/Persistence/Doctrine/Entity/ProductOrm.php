<?php

namespace Dadinaks\Product\Infrastructure\Persistence\Doctrine\Entity;

use Dadinaks\Product\Domain\Entity\Product;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'Procuct')]
final class ProductOrm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'uuid', unique: true)]
    private string $uid;

    #[ORM\Column(length: 255)]
    private string $code;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    #[ORM\Column(type: 'integer')]
    private int $threshold;

    #[ORM\Column(type: 'boolean')]
    private bool $isDeleted = false;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    public static function fromDomain(Product $product): self
    {
        $orm = new self();
        $orm->uid   = $product->getUid();
        $orm->code  = $product->getCode();
        $orm->name  = $product->getName();
        $orm->quantity   = $product->getQuantity();
        $orm->threshold  = $product->getThreshold();
        $orm->isDeleted  = $product->isDeleted();
        $orm->createdAt  = $product->getCreatedAt();
        $orm->updatedAt  = $product->getUpdatedAt();
        $orm->deletedAt  = $product->getDeletedAt();

        return $orm;
    }

    public function toDomain(): Product
    {
        return Product::fromState([
            'uid'       => $this->uid,
            'code'      => $this->code,
            'name'      => $this->name,
            'quantity'  => $this->quantity,
            'threshold' => $this->threshold,
            'isDeleted' => $this->isDeleted,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'deletedAt' => $this->deletedAt
        ]);
    }

    public function setCode(string $code): string
    {
        return $this->code = $code;
    }

    public function setName(string $name): string
    {
        return $this->name = $name;
    }

    public function setQuantity(int $quantity): int
    {
        return $this->quantity = $quantity;
    }

    public function setThreshold(int $threshold): int
    {
        return $this->threshold = $threshold;
    }

    public function setDeleted(bool $isDeleted): bool
    {
        return $this->isDeleted = $isDeleted;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): ?\DateTimeImmutable
    {
        return $this->updatedAt = $updatedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): ?\DateTimeImmutable
    {
        return $this->deletedAt = $deletedAt;
    }
}
