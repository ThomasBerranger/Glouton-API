<?php

namespace App\Tests\Unit\Validation;

use App\Entity\Product\CustomProduct;
use App\Entity\Product\ScannedProduct;
use App\Tests\BaseTest;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductTest extends BaseTest
{
    public function testScannedProductValidationGroup(): void
    {
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $scannedProduct = new ScannedProduct();
        $scannedProduct->setNutriscore('z')
            ->setNovagroup(5)
            ->setEcoscore('z');

        $errors = $validator->validate($scannedProduct, groups: ['create']);

        $this->assertSame(['barcode', 'nutriscore', 'novagroup', 'ecoscore', 'name'], array_map(function (ConstraintViolation $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($errors)));
    }

    public function testCustomProductValidationGroup(): void
    {
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $customProduct = new CustomProduct();

        $errors = $validator->validate($customProduct, groups: ['create']);

        $this->assertSame(['name'], array_map(function (ConstraintViolation $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($errors)));
    }
}
