<?php

namespace App\Controller;

use App\Entity\Product\CustomProduct;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProductController extends BaseController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/products', name: 'products', methods: ['get'])]
    #[IsGranted('ROLE_USER')]
    public function index(): JsonResponse
    {
        $products = $this->getCurrentUser()->getProducts();

        return $this->json($products, Response::HTTP_OK, [], ['groups' => 'products.show']);
    }

    #[Route('/products', name: 'products', methods: ['post'])]
    #[IsGranted('ROLE_USER')]
    public function create(#[MapRequestPayload] CustomProduct $product): JsonResponse
    {
        $product->setOwner($this->getCurrentUser());

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->json($product, Response::HTTP_CREATED, [], ['groups' => 'products.show']);
    }
}
