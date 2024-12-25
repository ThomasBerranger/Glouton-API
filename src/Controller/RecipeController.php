<?php

namespace App\Controller;

use App\Entity\Product\Product;
use App\Entity\Recipe;
use App\Utils\ValidatorTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class RecipeController extends BaseController
{
    use ValidatorTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/recipes', name: 'recipes.create', methods: ['post'], format: 'json')]
    #[IsGranted('ROLE_USER')]
    public function create(#[MapRequestPayload(
        serializationContext: ['edit_recipe'],
        validationGroups: ['create'],
    )] Recipe $recipe): JsonResponse
    {
        $this->entityManager->persist($recipe);
        $this->entityManager->flush();

        return $this->json($recipe, Response::HTTP_CREATED, context: ['groups' => 'show_recipe']);
    }

    #[Route('/recipes', name: 'recipes.index', methods: ['get'], format: 'json')]
    #[IsGranted('ROLE_USER')]
    public function index(): JsonResponse
    {
        return $this->json($this->getCurrentUser()->getRecipes(), Response::HTTP_OK, context: ['groups' => 'show_recipe']);
    }

    #[Route('/recipes/{id}', name: 'recipes.show', methods: ['get'], format: 'json')]
    #[IsGranted('edit', 'recipe')]
    public function show(Recipe $recipe): JsonResponse
    {
        return $this->json($recipe, Response::HTTP_OK, context: ['groups' => 'show_recipe']);
    }

    #[Route('/recipes/{id}', name: 'recipes.edit', methods: ['patch'], format: 'json')]
    #[IsGranted('edit', 'recipe')]
    public function edit(Recipe $recipe, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $recipe = $serializer->deserialize($request->getContent(), Recipe::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $recipe,
            AbstractNormalizer::GROUPS => 'edit_recipe',
        ]);

        if ($errors = $this->validate($recipe)) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->entityManager->flush();

        return $this->json($recipe, Response::HTTP_OK, context: ['groups' => 'show_recipe']);
    }

    #[Route('/recipes/{id}', name: 'recipes.delete', methods: ['delete'], format: 'json')]
    #[IsGranted('edit', 'recipe')]
    public function delete(Recipe $recipe): JsonResponse
    {
        $this->entityManager->remove($recipe);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    #[Route('/recipes/{recipe}/{product}', name: 'recipes.toggle-product', methods: ['patch'], format: 'json')]
    #[IsGranted('edit', 'recipe')]
    public function toggleProduct(Recipe $recipe, Product $product): JsonResponse
    {
        if (!$recipe->getProducts()->contains($product)) {
            $recipe->addProduct($product);
        } else {
            $recipe->removeProduct($product);
        }

        $this->entityManager->flush();

        return $this->json($recipe, Response::HTTP_OK, context: ['groups' => 'show_recipe']);
    }
}
