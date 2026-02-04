<?php

namespace Dadinaks\Entry\Infrastructure\Persistence\Doctrine\Repository;

use Dadinaks\Entry\Domain\Entity\Entry;
use Dadinaks\Entry\Infrastructure\Persistence\Doctrine\Entity\EntryOrm;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\User\Infrastructure\Persistence\Doctrine\Entity\UserOrm as User;
use Dadinaks\Product\Infrastructure\Persistence\Doctrine\Entity\ProductOrm as Product;
use Doctrine\ORM\EntityManagerInterface;

final class EntryRepository implements RepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function save(object $entity): void
    {
        if (!$entity instanceof Entry) {
            throw new \InvalidArgumentException('Expected ' . Entry::class);
        }

        $orm = $this->entityManager->getRepository(EntryOrm::class)->findOneBy(['uid' => $entity->getUid()]);

        if ($orm) {
            $orm->setQuantity($entity->getQuantity());
            $orm->setUpdatedAt($entity->getUpdatedAt());
            $orm->setDeletedAt($entity->getDeletedAt());
            $orm->setIsDeleted($entity->isDeleted());

            if ($entity->getUpdatedBy()) {
                $updatedBy = $this->entityManager
                    ->getRepository(User::class)
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
                    ->getRepository(User::class)
                    ->findOneBy(['uid' => $entity->getDeletedBy()->getUid()]);

                if (!$updatedBy) {
                    throw new \RuntimeException(
                        sprintf('User with UID "%s" not found', $entity->getDeletedBy()->getUid())
                    );
                }

                $orm->setDeletedBy($deletedBy);
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

            $orm = EntryOrm::fromDomain($entity, $product, $createdBy);
            $this->entityManager->persist($orm);
        }

        $this->entityManager->flush();
    }

    public function findByUid(string $uid): ?object
    {
        $orm = $this->entityManager
            ->getRepository(EntryOrm::class)
            ->findOneBy(['uid' => $uid]);

        return $orm?->toDomain();
    }

    public function findOneBy(array $criteria): ?object
    {
        $orm = $this->entityManager
            ->getRepository(EntryOrm::class)
            ->findOneBy($criteria);

        return $orm?->toDomain();
    }

    public function findAll(): array
    {
        return array_map(
            fn(EntryOrm $orm) => $orm->toDomain(),
            $this->entityManager->getRepository(EntryOrm::class)->findAll()
        );
    }

    public function count(array $criteria = []): ?int
    {
        return $this->entityManager
            ->getRepository(EntryOrm::class)
            ->count($criteria);
    }
}
