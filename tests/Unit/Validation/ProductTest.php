<?php

namespace App\Tests\Unit\Validation;

use App\Entity\ExpirationDate;
use App\Entity\Product\CustomProduct;
use App\Entity\Product\ScannedProduct;
use App\Tests\BaseTest;
use App\Tests\User;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductTest extends BaseTest
{
    public function testScannedProductValidationGroup(): void
    {
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $scannedProduct = new ScannedProduct();

        $errors = $validator->validate($scannedProduct, groups: ['create']);

        $this->assertSame(['barcode', 'name'], array_map(function (ConstraintViolation $violation) {
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
