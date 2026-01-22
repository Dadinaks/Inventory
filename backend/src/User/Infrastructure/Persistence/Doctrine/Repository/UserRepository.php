<?php

namespace Dadinaks\User\Infrastructure\Persistence\Doctrine\Repository;

use Dadinaks\Shared\Domain\Repository\RepositoryInterface;
use Dadinaks\User\Domain\Entity\User;
use Dadinaks\User\Infrastructure\Persistence\Doctrine\Entity\UserOrm;
use Dadinaks\Role\Infrastructure\Persistence\Doctrine\Entity\RoleOrm;
use Dadinaks\User\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class UserRepository implements RepositoryInterface, UserRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function save(object $entity): void
    {
        if (!$entity instanceof User) {
            throw new \InvalidArgumentException('Expected ' . UserOrm::class);
        }

        $role = $this->entityManager
            ->getRepository(RoleOrm::class)
            ->findOneBy(['uid' => $entity->getRole()->getUid()]);

        if (!$role) {
            throw new \RuntimeException(
                sprintf('Role with UID "%s" not found', $entity->getRole()->getUid())
            );
        }

        $orm = UserOrm::fromDomain($entity, $role);
        $this->entityManager->persist($orm);

        $this->entityManager->flush();
    }

    public function findByUid(string $uid): ?object
    {
        $orm = $this->entityManager
            ->getRepository(UserOrm::class)
            ->findOneBy(['uid' => $uid]);

        return $orm?->toDomain();
    }

    public function findOneBy(array $criteria): ?object
    {
        $orm = $this->entityManager
            ->getRepository(UserOrm::class)
            ->findOneBy($criteria);

        return $orm?->toDomain();
    }

    public function findAll(): array
    {
        return array_map(
            fn(UserOrm $orm) => $orm->toDomain(),
            $this->entityManager->getRepository(UserOrm::class)->findAll()
        );
    }

    public function count(array $criteria = []): ?int
    {
        return $this->entityManager
            ->getRepository(UserOrm::class)
            ->count($criteria);
    }
}
