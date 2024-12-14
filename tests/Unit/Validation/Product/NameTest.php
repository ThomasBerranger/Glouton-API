<?php

namespace App\Tests\Unit\Validation\Product;

use App\Entity\Product\CustomProduct;
use App\Tests\BaseTest;
use App\Tests\Unit\Validation\ValidationGroups;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NameTest extends BaseTest
{
    /**
     * @dataProvider groupsProvider
     */
    public function testEmpty(string $group): void
    {
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $customProduct = new CustomProduct();

        $errors = $validator->validate($customProduct, groups: $group);

        $this->assertContains('name', array_map(function (ConstraintViolation $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($errors)));
    }

    /**
     * @dataProvider groupsProvider
     */
    public function testTooLong(string $group): void
    {
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $customProduct = new CustomProduct();
        $customProduct->setName(str_repeat('a', 256));

        $errors = $validator->validate($customProduct, groups: $group);

        $this->assertContains('name', array_map(function (ConstraintViolation $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($errors)));
    }

    private function groupsProvider(): array
    {
        return ValidationGroups::GROUPS;
    }
}
