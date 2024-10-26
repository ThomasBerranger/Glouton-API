<?php

namespace App\Tests\Unit\Serialization;

use App\Entity\ExpirationDate;
use App\Entity\Product\CustomProduct;
use App\Entity\Product\ScannedProduct;
use App\Tests\BaseTest;
use App\Tests\User;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProductTest extends BaseTest
{
    public function testScannedProductDeserializationGroup(): void
    {
        $normalizer = $this->getContainer()->get(NormalizerInterface::class);

        $scannedProduct = new ScannedProduct();
        $scannedProduct
            ->setName('Product name')
            ->setOwner($this->getUser(User::USER))
            ->setDescription('Product description')
            ->setImage('https://product-image-url')
            ->setFinishedAt(new \DateTime('01/01/2025'))
            ->setAddedToListAt(new \DateTime('01/01/2025'))
            ->setBarcode('123')
            ->setNutriscore('A')
            ->setEcoscore(1)
            ->setNovagroup(4)
            ->addExpirationDate((new ExpirationDate())->setDate(new \DateTime('01/01/2025')));

        $normalizedScannedProduct = $normalizer->normalize($scannedProduct, context: ['groups' => ['show_product']]);

        $this->assertArrayNotHasKey('id', $normalizedScannedProduct);
        $this->assertArrayNotHasKey('owner', $normalizedScannedProduct);
        $this->assertArrayNotHasKey('barcode', $normalizedScannedProduct);

        $this->assertArrayNotHasKey('id', $normalizedScannedProduct['expirationDates']);
        $this->assertArrayNotHasKey('product', $normalizedScannedProduct['expirationDates']);

        $normalizedScannedProduct = $normalizer->normalize($scannedProduct, context: ['groups' => ['edit_product']]);

        $this->assertArrayNotHasKey('id', $normalizedScannedProduct);
        $this->assertArrayNotHasKey('owner', $normalizedScannedProduct);

        $this->assertArrayNotHasKey('id', $normalizedScannedProduct['expirationDates']);
        $this->assertArrayNotHasKey('product', $normalizedScannedProduct['expirationDates']);
    }

    public function testCustomProductDeserializationGroup(): void
    {
        $normalizer = $this->getContainer()->get(NormalizerInterface::class);

        $customProduct = new CustomProduct();
        $customProduct
            ->setName('Product name')
            ->setOwner($this->getUser(User::USER))
            ->setDescription('Product description')
            ->setImage('https://product-image-url')
            ->setFinishedAt(new \DateTime('01/01/2025'))
            ->setAddedToListAt(new \DateTime('01/01/2025'))
            ->addExpirationDate((new ExpirationDate())->setDate(new \DateTime('01/01/2025')));

        $normalizedCustomProduct = $normalizer->normalize($customProduct, context: ['groups' => ['show_product']]);

        $this->assertArrayNotHasKey('id', $normalizedCustomProduct);
        $this->assertArrayNotHasKey('owner', $normalizedCustomProduct);

        $this->assertArrayNotHasKey('id', $normalizedCustomProduct['expirationDates']);
        $this->assertArrayNotHasKey('product', $normalizedCustomProduct['expirationDates']);

        $normalizedCustomProduct = $normalizer->normalize($customProduct, context: ['groups' => ['edit_product']]);

        $this->assertArrayNotHasKey('id', $normalizedCustomProduct);
        $this->assertArrayNotHasKey('owner', $normalizedCustomProduct);

        $this->assertArrayNotHasKey('id', $normalizedCustomProduct['expirationDates']);
        $this->assertArrayNotHasKey('product', $normalizedCustomProduct['expirationDates']);
    }
}
