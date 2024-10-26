<?php

namespace App\Controller;

use App\Entity\Product\ScannedProduct;
use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RecipeController extends BaseController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/recipes', name: 'recipes.create', methods: ['post'], format: 'json')]
    #[IsGranted('ROLE_USER')]
    public function create(#[MapRequestPayload(
        serializationContext: ['edit_recipe'],
        validationGroups: ['create'],
    )] Recipe $recipe): JsonResponse
    {
        $recipe->setOwner($this->getCurrentUser());

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
}
