<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
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
            // @phpstan-ignore-next-line
            ->setOwner($this->getReference(UserFixtures::ADMIN__REFERENCE))
            ->setName($faker->word())
            ->setDescription($faker->text())
            ->setDuration($faker->dateTimeBetween(
                (new \DateTime('2000-01-01 0:0'))->modify('+1 minute'),
                (new \DateTime('2000-01-01 0:0'))->modify('+12 hours')
            ))
            // @phpstan-ignore-next-line
            ->addProduct($this->getReference(ProductFixtures::PRODUCT_REFERENCE));

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
