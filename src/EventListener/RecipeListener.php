<?php

namespace App\EventListener;

use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: 'prePersist', entity: Recipe::class)]
readonly class RecipeListener
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function prePersist(Recipe $recipe, PrePersistEventArgs $event): void
    {
        $user = $this->security->getUser();

        if ($user instanceof User) {
            $recipe->setOwner($user);
        }
    }
}
