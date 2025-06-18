<?php

namespace App\DataFixtures;

use App\Entity\Product\CustomProduct;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create();

        $recipe = new Recipe();

        $recipe
            ->setOwner($this->getReference(UserFixtures::ADMIN_REFERENCE, User::class))
            ->setName($faker->word())
            ->setDescription($faker->text())
            ->setDuration($faker->dateTimeBetween(
                (new \DateTime('2000-01-01 0:0'))->modify('+1 minute'),
                (new \DateTime('2000-01-01 0:0'))->modify('+12 hours')
            ))
            ->addProduct($this->getReference(ProductFixtures::PRODUCT_REFERENCE, CustomProduct::class));

        $manager->persist($recipe);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ProductFixtures::class,
        ];
    }
}
