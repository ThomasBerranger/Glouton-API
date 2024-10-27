<?php

namespace App\Tests\Unit\Serialization;

use App\Entity\ExpirationDate;
use App\Entity\Product\CustomProduct;
use App\Entity\Product\ScannedProduct;
use App\Tests\BaseTest;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProductTest extends BaseTest
{
    public function testScannedProductSerializationGroup(): void
    {
        $normalizer = $this->getContainer()->get(NormalizerInterface::class);

        $scannedProduct = new ScannedProduct();
        $scannedProduct->addExpirationDate(new ExpirationDate());

        $normalizedScannedProduct = $normalizer->normalize($scannedProduct, context: ['groups' => ['show_product']]);

        $this->assertSame(['nutriscore', 'novagroup', 'ecoscore', 'name', 'description', 'image', 'finishedAt', 'addedToListAt', 'expirationDates'], array_keys($normalizedScannedProduct));
        $this->assertSame(['date'], array_keys($normalizedScannedProduct['expirationDates'][0]));

        $normalizedScannedProduct = $normalizer->normalize($scannedProduct, context: ['groups' => ['edit_product']]);

        $this->assertSame(['nutriscore', 'novagroup', 'ecoscore', 'name', 'description', 'image', 'finishedAt', 'addedToListAt', 'expirationDates'], array_keys($normalizedScannedProduct));
        $this->assertSame(['date'], array_keys($normalizedScannedProduct['expirationDates'][0]));
    }

    public function testCustomProductSerializationGroup(): void
    {
        $normalizer = $this->getContainer()->get(NormalizerInterface::class);

        $customProduct = new CustomProduct();
        $customProduct->addExpirationDate(new ExpirationDate());

        $normalizedCustomProduct = $normalizer->normalize($customProduct, context: ['groups' => ['show_product']]);

        $this->assertSame(['name', 'description', 'image', 'finishedAt', 'addedToListAt', 'expirationDates'], array_keys($normalizedCustomProduct));
        $this->assertSame(['date'], array_keys($normalizedCustomProduct['expirationDates'][0]));
    }
}
