<?php

namespace App\DataFixtures;

use App\Entity\Product\CustomProduct;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public const PRODUCT_REFERENCE = 'product';

    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create();

        $product = new CustomProduct();

        $product
            ->setOwner($this->getReference(UserFixtures::ADMIN_REFERENCE, User::class))
            ->setName($faker->word())
            ->setImage($faker->imageUrl())
            ->setDescription($faker->text())
            ->setFinishedAt($faker->dateTimeBetween('now', '+1 month'))
            ->setAddedToListAt($faker->dateTimeBetween('now', '+1 month'));

        $manager->persist($product);
        $manager->flush();

        $this->addReference(self::PRODUCT_REFERENCE, $product);
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
