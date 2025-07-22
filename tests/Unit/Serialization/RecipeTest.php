<?php

namespace App\Tests\Unit\Serialization;

use App\Entity\Product\CustomProduct;
use App\Entity\Recipe;
use App\Tests\BaseTest;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class RecipeTest extends BaseTest
{
    public function testSerializationGroup(): void
    {
        $normalizer = $this->getContainer()->get(NormalizerInterface::class);

        $recipe = new Recipe();
        $recipe->addProduct(new CustomProduct());

        $normalizedRecipe = $normalizer->normalize($recipe, context: ['groups' => ['show_recipe']]);

        $this->assertSame(['id', 'name', 'description', 'duration', 'products'], array_keys($normalizedRecipe));
        $this->assertSame(['id', 'name'], array_keys($normalizedRecipe['products'][0]));
    }
}
