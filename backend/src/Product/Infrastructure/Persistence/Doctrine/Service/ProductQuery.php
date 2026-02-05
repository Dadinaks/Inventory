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
                e.created_at AS date,
                \'entry\' AS type,
                e.created_by_id AS user                
            FROM Dadinaks\Product\Infrastructure\Persistence\Doctrine\Entity\ProductOrm p
            JOIN Dadinaks\Entry\Infrastructure\Persistence\Doctrine\Entity\EntryOrm e WITH e.product = p
            WHERE p.uid = :uid',
            array('uid' => $uid)
        );

        $exitQuery = $entityManager->createQuery(
            'SELECT
                ex.uid,
                ex.quantity,
                ex.created_at AS date,
                \'exits\' AS type,
                ex.created_by_id AS user
            FROM Dadinaks\Product\Infrastructure\Persistence\Doctrine\Entity\ProductOrm p
            JOIN Dadinaks\Exits\Infrastructure\Persistence\Doctrine\Entity\ExitOrm ex WITH ex.product = p
            WHERE p.uid = :uid',
            array('uid' => $uid)
        );

        $history = array_merge($entryQuery->getResult(), $exitQuery->getResult());

        usort($history, fn($a, $b) => $b['date'] <=> $a['date']);

        return $history;
    }
}
