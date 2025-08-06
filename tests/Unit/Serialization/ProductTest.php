<?php

namespace App\Tests\Unit\Serialization;

use App\Entity\ExpirationDate;
use App\Entity\Product\Category;
use App\Entity\Product\CustomProduct;
use App\Entity\Product\ScannedProduct;
use App\Entity\Recipe;
use App\Tests\BaseTest;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProductTest extends BaseTest
{
    public function testScannedProductSerializationGroup(): void
    {
        $normalizer = $this->getContainer()->get(NormalizerInterface::class);

        $scannedProduct = new ScannedProduct();
        $scannedProduct->addExpirationDate(new ExpirationDate());
        $scannedProduct->addRecipe(new Recipe());
        $scannedProduct->setCategory($this->entityManager->getRepository(Category::class)->findOneBy([]));

        $normalizedScannedProduct = $normalizer->normalize($scannedProduct, context: ['groups' => ['show_product']]);

        $this->assertSame(['nutriscore', 'novagroup', 'ecoscore', 'id', 'name', 'description', 'image', 'finishedAt', 'addedToListAt', 'expirationDates', 'recipes', 'category', 'scanned', 'closestExpirationDate'], array_keys($normalizedScannedProduct));
        $this->assertSame(['id', 'name', 'duration'], array_keys($normalizedScannedProduct['recipes'][0]));
        $this->assertSame(['date'], array_keys($normalizedScannedProduct['expirationDates'][0]));
        $this->assertSame(['id', 'name'], array_keys($normalizedScannedProduct['category']));

        $normalizedScannedProduct = $normalizer->normalize($scannedProduct, context: ['groups' => ['edit_product']]);

        $this->assertSame(['nutriscore', 'novagroup', 'ecoscore', 'name', 'description', 'image', 'finishedAt', 'addedToListAt', 'expirationDates', 'category'], array_keys($normalizedScannedProduct));
        $this->assertSame(['date'], array_keys($normalizedScannedProduct['expirationDates'][0]));
    }

    public function testCustomProductSerializationGroup(): void
    {
        $normalizer = $this->getContainer()->get(NormalizerInterface::class);

        $customProduct = new CustomProduct();
        $customProduct->addExpirationDate(new ExpirationDate());
        $customProduct->addRecipe(new Recipe());
        $customProduct->setCategory($this->entityManager->getRepository(Category::class)->findOneBy([]));

        $normalizedCustomProduct = $normalizer->normalize($customProduct, context: ['groups' => ['show_product']]);

        $this->assertSame(['id', 'name', 'description', 'image', 'finishedAt', 'addedToListAt', 'expirationDates', 'recipes', 'category', 'scanned', 'closestExpirationDate'], array_keys($normalizedCustomProduct));
        $this->assertSame(['id', 'name', 'duration'], array_keys($normalizedCustomProduct['recipes'][0]));
        $this->assertSame(['date'], array_keys($normalizedCustomProduct['expirationDates'][0]));
        $this->assertSame(['id', 'name'], array_keys($normalizedCustomProduct['category']));

        $normalizedCustomProduct = $normalizer->normalize($customProduct, context: ['groups' => ['edit_product']]);

        $this->assertSame(['name', 'description', 'image', 'finishedAt', 'addedToListAt', 'expirationDates', 'category'], array_keys($normalizedCustomProduct));
        $this->assertSame(['date'], array_keys($normalizedCustomProduct['expirationDates'][0]));
    }
}
