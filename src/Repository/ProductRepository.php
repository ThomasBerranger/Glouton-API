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
            ->getResult();
    }

    public function findByOwnerWithAndOrderedByShoppingList(User $user, ?bool $count = false): mixed
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.owner = :userId')
            ->setParameter('userId', $user->getId(), 'uuid')
            ->andWhere('p.addedToListAt is not null')
            ->orderBy('p.addedToListAt');

        if ($count) {
            return $qb
                ->select('COUNT(p.id)')
                ->getQuery()
                ->getSingleScalarResult();
        }

        return $qb->getQuery()->getResult();
    }
}
