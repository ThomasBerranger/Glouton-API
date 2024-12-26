<?php

namespace App\EventListener;

use App\Entity\Product\Product;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: 'prePersist', entity: Product::class)]
readonly class ProductListener
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function prePersist(Product $product, PrePersistEventArgs $event): void
    {
        $user = $this->security->getUser();

        if ($user instanceof User) {
            $product->setOwner($user);
        }
    }
}
