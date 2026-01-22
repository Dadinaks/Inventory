<?php

namespace Dadinaks\Role\Infrastructure\Persistence\Doctrine\Repository;

use Dadinaks\Role\Domain\Entity\Role;
use Dadinaks\Role\Domain\Repository\RoleRepositoryInterface;
use Dadinaks\Role\Infrastructure\Persistence\Doctrine\Entity\RoleOrm;
use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class RoleRepository implements RepositoryInterface, RoleRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function save(object $entity): void
    {
        if (!$entity instanceof Role) {
            throw new \InvalidArgumentException('Expected ' . RoleOrm::class);
        }

        $orm = RoleOrm::fromDomain($entity);
        $this->entityManager->persist($orm);

        $this->entityManager->flush();
    }

    public function findByUid(string $uid): ?object
    {
        $orm = $this->entityManager
            ->getRepository(RoleOrm::class)
            ->findOneBy(['uid' => $uid]);

        return $orm?->toDomain();
    }

    public function findOneBy(array $criteria): ?object
    {
        $orm = $this->entityManager
            ->getRepository(RoleOrm::class)
            ->findOneBy($criteria);

        return $orm?->toDomain();
    }

    public function findAll(): array
    {
        return array_map(
            fn(RoleOrm $orm) => $orm->toDomain(),
            $this->entityManager->getRepository(RoleOrm::class)->findAll()
        );
    }

    public function count(array $criteria = []): ?int
    {
        return $this->entityManager
            ->getRepository(RoleOrm::class)
            ->count($criteria);
    }
}
