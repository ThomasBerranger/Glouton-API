<?php

namespace App\Tests\Unit\Validation\Product;

use App\Entity\Product\ScannedProduct;
use App\Tests\BaseTest;
use App\Tests\Unit\Validation\ValidationGroups;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BarcodeTest extends BaseTest
{
    /**
     * @dataProvider groupsProvider
     */
    public function testEmpty(string $group): void
    {
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $scannedProduct = new ScannedProduct();

        $errors = $validator->validate($scannedProduct, groups: $group);

        $this->assertContains('barcode', array_map(function (ConstraintViolation $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($errors)));
    }

    /**
     * @dataProvider groupsProvider
     */
    public function testNoString(string $group): void
    {
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $scannedProduct = new ScannedProduct();
        $scannedProduct->setName('name')->setBarcode(false);

        $errors = $validator->validate($scannedProduct, groups: $group);

        $this->assertContains('barcode', array_map(function (ConstraintViolation $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($errors)));
    }

    private function groupsProvider(): array
    {
        return ValidationGroups::GROUPS;
    }
}
