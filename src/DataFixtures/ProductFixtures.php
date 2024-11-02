<?php

namespace App\DataFixtures;

use App\Entity\Product\CustomProduct;
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
            // @phpstan-ignore-next-line
            ->setOwner($this->getReference(UserFixtures::ADMIN__REFERENCE))
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
