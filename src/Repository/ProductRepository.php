<?php

namespace App\Repository;

use App\Entity\Product\Product;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findByOwnerOrderedByClosestExpirationDate(User $user, int $limit, int $offset): mixed
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.expirationDates', 'ed')
            ->where('p.owner = :userId')
            ->setParameter('userId', $user->getId(), 'uuid')
            ->groupBy('p.id')
            ->orderBy('min(ed.date)')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
