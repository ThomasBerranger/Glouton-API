<?php

namespace App\Tests\Unit\Validation\Recipe;

use App\Entity\Recipe;
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

        $recipe = new Recipe();
        $errors = $validator->validate($recipe, groups: $group);

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

        $recipe = new Recipe();
        $recipe->setName(str_repeat('a', 256));

        $errors = $validator->validate($recipe, groups: $group);

        $this->assertContains('name', array_map(function (ConstraintViolation $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($errors)));
    }

    private function groupsProvider(): array
    {
        return ValidationGroups::GROUPS;
    }
}
