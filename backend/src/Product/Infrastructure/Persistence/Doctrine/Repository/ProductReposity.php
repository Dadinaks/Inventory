<?php

namespace Dadinaks\Product\Infrastructure\Persistence\Doctrine\Repository;

use Dadinaks\Product\Domain\Entity\Product;
use Dadinaks\Product\Infrastructure\Persistence\Doctrine\Entity\ProductOrm;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class ProductReposity implements RepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function save(object $entity): void
    {
        if (!$entity instanceof Product) {
            throw new \InvalidArgumentException('Expected ' . Product::class);
        }

        $orm = ProductOrm::fromDomain($entity);

        $this->entityManager->persist($orm);
        $this->entityManager->flush();
    }

    public function findByUid(string $uid): ?object
    {
        $orm = $this->entityManager
            ->getRepository(ProductOrm::class)
            ->findOneBy(['uid' => $uid]);

        return $orm?->toDomain();
    }

    public function findOneBy(array $criteria): ?object
    {
        $orm = $this->entityManager
            ->getRepository(ProductOrm::class)
            ->findOneBy($criteria);

        return $orm?->toDomain();
    }

    public function findAll(): array
    {
        return array_map(
            fn(ProductOrm $orm) => $orm->toDomain(),
            $this->entityManager->getRepository(ProductOrm::class)->findAll()
        );
    }

    public function count(array $criteria = []): ?int
    {
        return $this->entityManager
            ->getRepository(ProductOrm::class)
            ->count($criteria);
    }
}
