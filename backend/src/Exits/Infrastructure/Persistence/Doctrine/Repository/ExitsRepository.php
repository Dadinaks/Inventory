<?php

namespace Dadinaks\Exits\Infrastructure\Persistence\Doctrine\Repository;

use Dadinaks\Exits\Domain\Entity\Exits;
use Dadinaks\Exits\Infrastructure\Persistence\Doctrine\Entity\ExitsOrm;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\User\Infrastructure\Persistence\Doctrine\Entity\UserOrm as User;
use Dadinaks\Product\Infrastructure\Persistence\Doctrine\Entity\ProductOrm as Product;
use Doctrine\ORM\EntityManagerInterface;

final class ExitsRepository implements RepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function save(object $entity): void
    {
        if (!$entity instanceof Exits) {
            throw new \InvalidArgumentException('Expected ' . Exits::class);
        }

        $orm = $this->entityManager->getRepository(ExitsOrm::class)->findOneBy(['uid' => $entity->getUid()]);

        if ($orm) {
            $orm->setQuantity(quantity: $entity->getQuantity());
            $orm->setUpdatedAt(updatedAt: $entity->getUpdatedAt());
            $orm->setDeletedAt(deletedAt: $entity->getDeletedAt());
            $orm->setIsDeleted(isDeleted: $entity->isDeleted());

            if ($entity->getUpdatedBy()) {
                $updatedBy = $this->entityManager
                    ->getRepository(User::class)
                    ->findOneBy(['uid' => $entity->getUpdatedBy()->getUid()]);

                if (!$updatedBy) {
                    throw new \RuntimeException(
                        sprintf('User with UID "%s" not found', $entity->getUpdatedBy()->getUid())
                    );
                }

                $orm->setUpdatedBy(updatedBy: $updatedBy);
            }

            if ($entity->getDeletedBy()) {
                $deletedBy = $this->entityManager
                    ->getRepository(User::class)
                    ->findOneBy(['uid' => $entity->getDeletedBy()->getUid()]);

                if (!$deletedBy) {
                    throw new \RuntimeException(
                        sprintf('User with UID "%s" not found', $entity->getDeletedBy()->getUid())
                    );
                }

                $orm->setDeletedBy(deletedBy:$deletedBy);
            }
        } else {
            $product = $this->entityManager
                ->getRepository(Product::class)
                ->findOneBy(['uid' => $entity->getProduct()->getUid()]);

            if (!$product) {
                throw new \RuntimeException("Product ORM not found for UID: " . $entity->getProduct()->getUid());
            }

            $createdBy = $this->entityManager
                ->getRepository(User::class)
                ->findOneBy(['uid' => $entity->getCreatedBy()->getUid()]);

            if (!$createdBy) {
                throw new \RuntimeException(
                    sprintf('User with UID "%s" not found', $entity->getCreatedBy()->getUid())
                );
            }

            $orm = ExitsOrm::fromDomain(exits: $entity, product: $product, createdBy: $createdBy);
            $this->entityManager->persist(object: $orm);
        }

        $this->entityManager->flush();
    }

    public function findByUid(string $uid): ?object
    {
        $orm = $this->entityManager
            ->getRepository(ExitsOrm::class)
            ->findOneBy(['uid' => $uid]);

        return $orm?->toDomain();
    }

    public function findOneBy(array $criteria): ?object
    {
        $orm = $this->entityManager
            ->getRepository(ExitsOrm::class)
            ->findOneBy($criteria);

        return $orm?->toDomain();
    }

    public function findAll(): array
    {
        return array_map(
            fn(ExitsOrm $orm) => $orm->toDomain(),
            $this->entityManager->getRepository(ExitsOrm::class)->findAll()
        );
    }

    public function count(array $criteria = []): ?int
    {
        return $this->entityManager
            ->getRepository(ExitsOrm::class)
            ->count($criteria);
    }
}
