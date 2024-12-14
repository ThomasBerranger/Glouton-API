<?php

namespace App\Tests\Unit\Validation\Product;

use App\Entity\Product\ScannedProduct;
use App\Tests\BaseTest;
use App\Tests\Unit\Validation\ValidationGroups;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NovagroupTest extends BaseTest
{
    /**
     * @dataProvider groupsProvider
     */
    public function testWrongValue(string $group): void
    {
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $scannedProduct = new ScannedProduct();
        $scannedProduct->setName('name')->setNovagroup(0);

        $errors = $validator->validate($scannedProduct, groups: $group);

        $this->assertContains('novagroup', array_map(function (ConstraintViolation $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($errors)));
    }

    private function groupsProvider(): array
    {
        return ValidationGroups::GROUPS;
    }
}
