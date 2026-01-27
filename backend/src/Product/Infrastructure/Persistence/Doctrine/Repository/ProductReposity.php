<?php

namespace Dadinaks\Product\Infrastructure\Persistence\Doctrine\Repository;

use Dadinaks\Product\Domain\Entity\Product;
use Dadinaks\Product\Infrastructure\Persistence\Doctrine\Entity\ProductOrm;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\User\Infrastructure\Persistence\Doctrine\Entity\UserOrm;
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

        $orm = $this->entityManager->getRepository(ProductOrm::class)->findOneBy(['uid' => $entity->getUid()]);

        if ($orm) {
            $orm->setThreshold($entity->getThreshold());
            $orm->setDeleted($entity->isDeleted());
            $orm->setDeletedAt($entity->getDeletedAt());
            $orm->setUpdatedAt($entity->getUpdatedAt());

            if ($entity->getUpdatedBy()) {
                $updatedBy = $this->entityManager
                    ->getRepository(UserOrm::class)
                    ->findOneBy(['uid' => $entity->getUpdatedBy()->getUid()]);

                if (!$updatedBy) {
                    throw new \RuntimeException(
                        sprintf('User with UID "%s" not found', $entity->getUpdatedBy()->getUid())
                    );
                }

                $orm->setUpdatedBy($updatedBy);
            }

            if ($entity->getDeletedBy()) {
                $deletedBy = $this->entityManager
                    ->getRepository(UserOrm::class)
                    ->findOneBy(['uid' => $entity->getDeletedBy()->getUid()]);

                if (!$deletedBy) {
                    throw new \RuntimeException(
                        sprintf('User with UID "%s" not found', $entity->getDeletedBy()->getUid())
                    );
                }

                $orm->setDeletedBy($deletedBy);
            }
        } else {
            $createdBy = $this->entityManager
                ->getRepository(UserOrm::class)
                ->findOneBy(['uid' => $entity->getCreatedBy()->getUid()]);

            if (!$createdBy) {
                throw new \RuntimeException(
                    sprintf('User with UID "%s" not found', $entity->getCreatedBy()->getUid())
                );
            }

            $orm = ProductOrm::fromDomain($entity, $createdBy);
            $this->entityManager->persist($orm);
        }

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
