<?php

namespace Dadinaks\Product\Infrastructure\Persistence\Doctrine\Service;

use Dadinaks\Product\Infrastructure\Persistence\Doctrine\Entity\ProductOrm as Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ProductQuery extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function HistoryProduct(string $uid): array
    {
        $entityManager = $this->getEntityManager();

        $entryQuery = $entityManager->createQuery(
            'SELECT
                e.uid,
                e.quantity,
                e.createdAt AS date,
                \'entry\' AS type,
                u.uid AS user
            FROM Dadinaks\Product\Infrastructure\Persistence\Doctrine\Entity\ProductOrm p
            JOIN Dadinaks\Entry\Infrastructure\Persistence\Doctrine\Entity\EntryOrm e WITH e.product = p
            JOIN Dadinaks\User\Infrastructure\Persistence\Doctrine\Entity\UserOrm u WITH e.createdBy = u
            WHERE p.uid = :uid'
        );
        $entryQuery->setParameter('uid', $uid);
        $entryQueryResult = $entryQuery->getResult();

        $exitQuery = $entityManager->createQuery(
            'SELECT
                ex.uid,
                ex.quantity,
                ex.createdAt AS date,
                \'exits\' AS type,
                u.uid AS user
            FROM Dadinaks\Product\Infrastructure\Persistence\Doctrine\Entity\ProductOrm p
            JOIN Dadinaks\Exits\Infrastructure\Persistence\Doctrine\Entity\ExitsOrm ex WITH ex.product = p
            JOIN Dadinaks\User\Infrastructure\Persistence\Doctrine\Entity\UserOrm u WITH ex.createdBy = u
            WHERE p.uid = :uid'
        );
        $exitQuery->setParameter('uid', $uid);
        $exitQueryResult = $exitQuery->getResult();

        $history = array_merge($entryQueryResult, $exitQueryResult);

        usort($history, fn($a, $b) => $b['date'] <=> $a['date']);

        return $history;
    }
}
