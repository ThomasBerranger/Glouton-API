<?php

namespace App\Controller;

use App\Entity\Product\CustomProduct;
use App\Entity\Product\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class ProductController extends BaseController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/products', name: 'products.create', methods: ['post'])]
    #[IsGranted('ROLE_USER')]
    public function create(#[MapRequestPayload] CustomProduct $product): JsonResponse
    {
        $product->setOwner($this->getCurrentUser());

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->json($product, Response::HTTP_CREATED, context: ['groups' => 'products.show']);
    }

    #[Route('/products', name: 'products.index', methods: ['get'])]
    #[IsGranted('ROLE_USER')]
    public function index(): JsonResponse
    {
        $products = $this->getCurrentUser()->getProducts();

        return $this->json($products, Response::HTTP_OK, context: ['groups' => 'products.show']);
    }

    #[Route('/products/{id}', name: 'products.show', methods: ['get'])]
    #[IsGranted('view', 'product')]
    public function show(Product $product): JsonResponse
    {
        return $this->json($product, Response::HTTP_OK, context: ['groups' => 'products.show']);
    }

    #[Route('/products/{id}', name: 'products.edit', methods: ['patch'])]
    #[IsGranted('edit', 'product')]
    public function edit(Request $request, Product $product, SerializerInterface $serializer): JsonResponse
    {
        $product = $serializer->deserialize($request->getContent(), Product::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $product,
            'groups' => 'products.edit',
        ]);

        return $this->json($product, Response::HTTP_OK, context: ['groups' => 'products.show']);
    }

    #[Route('/products/{id}', name: 'products.delete', methods: ['delete'])]
    #[IsGranted('edit', 'product')]
    public function delete(CustomProduct $product): JsonResponse
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
