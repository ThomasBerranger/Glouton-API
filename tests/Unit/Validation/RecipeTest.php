<?php

namespace App\Tests\Unit\Validation;

use App\Entity\Recipe;
use App\Tests\BaseTest;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RecipeTest extends BaseTest
{
    public function testRecipeValidation(): void
    {
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $recipe = new Recipe();

        $errors = $validator->validate($recipe);

        $this->assertSame(['name'], array_map(function (ConstraintViolation $violation) {
            return $violation->getPropertyPath();
        }, iterator_to_array($errors)));
    }
}
