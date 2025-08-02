<?php

namespace App\Repository;

use App\Entity\Product\Product;
use App\Entity\User;
use App\Enum\ProductOrder;
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

    public function findByOwnerOrderedByClosestExpirationDate(User $user, int $limit, int $offset, ?ProductOrder $order = ProductOrder::ALL): mixed
    {
        $query = $this->createQueryBuilder('p')
            ->addSelect('MIN(ed.date) as HIDDEN closest_expiration_date');

        if (ProductOrder::ALL_WITH_EXPIRATION_DATE === $order) {
            $query->leftJoin('p.expirationDates', 'ed');
        } else {
            $query->innerJoin('p.expirationDates', 'ed');
        }

        return $query->where('p.owner = :userId')
            ->setParameter('userId', $user->getId(), 'uuid')
            ->groupBy('p.id')
            ->orderBy(
                in_array($order, [ProductOrder::NAME, ProductOrder::NAME_REVERSE]) ? 'p.name' : 'closest_expiration_date',
                in_array($order, [ProductOrder::ALL_REVERSE, ProductOrder::NAME_REVERSE]) ? 'DESC' : 'ASC'
            )
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
