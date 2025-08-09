<?php

namespace App\DataFixtures;

use App\Entity\Product\Category;
use App\Enum\Category as CategoryEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORY_REFERENCE = 'category';

    public function load(ObjectManager $manager): void
    {
        foreach (CategoryEnum::cases() as $categoryEnum) {
            $category = new Category();

            $category->setName($categoryEnum->value);

            if (CategoryEnum::OTHER === $categoryEnum) {
                $this->addReference(self::CATEGORY_REFERENCE, $category);
            }

            $manager->persist($category);
        }

        $manager->flush();
    }
}
