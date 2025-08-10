<?php

namespace App\Controller;

use App\Entity\Product\CustomProduct;
use App\Entity\Product\Product;
use App\Entity\Product\ScannedProduct;
use App\Enum\ProductOrder;
use App\Repository\Product\ProductRepository;
use App\Repository\Product\ScannedProductRepository;
use App\Security\Voter\ProductVoter;
use App\Utils\ValidatorTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class ProductController extends BaseController
{
    use ValidatorTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProductRepository $productRepository,
        private readonly ScannedProductRepository $scannedProductRepository,
    ) {
    }

    #[Route('/scanned-products', name: 'scanned.products.create', methods: ['post'], format: 'json')]
    #[IsGranted('ROLE_USER')]
    public function scannedCreate(#[MapRequestPayload(
        serializationContext: ['edit_product'],
        validationGroups: ['create'],
    )] ScannedProduct $product): JsonResponse
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->json($product, Response::HTTP_CREATED, context: ['groups' => 'show_product']);
    }

    #[Route('/custom-products', name: 'custom.products.create', methods: ['post'], format: 'json')]
    #[IsGranted('ROLE_USER')]
    public function customCreate(#[MapRequestPayload(
        serializationContext: ['edit_product'],
        validationGroups: ['create'],
    )] CustomProduct $product): JsonResponse
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->json($product, Response::HTTP_CREATED, context: ['groups' => 'show_product']);
    }

    #[Route('/products', name: 'products.index', methods: ['get'], format: 'json')]
    #[IsGranted('ROLE_USER')]
    public function index(
        #[MapQueryParameter] int $limit = 10,
        #[MapQueryParameter] int $offset = 0,
        #[MapQueryParameter] ProductOrder $order = ProductOrder::ALL_WITH_EXPIRATION_DATE,
    ): JsonResponse {
        $products = $this->productRepository->findByOwnerOrderedByClosestExpirationDate($this->getCurrentUser(), $limit, $offset, $order);

        return $this->json($products, Response::HTTP_OK, context: ['groups' => 'show_product']);
    }

    #[Route('/scanned-products', name: 'scanned.products.index', methods: ['get'], format: 'json')]
    #[IsGranted('ROLE_USER')]
    public function scannedProductSearch(
        #[MapQueryParameter] string $barcode,
    ): JsonResponse {
        $scannedProduct = $this->scannedProductRepository->findOneBy(['barcode' => $barcode]);

        $this->denyAccessUnlessGranted(ProductVoter::VIEW, $scannedProduct);

        return $this->json($scannedProduct, Response::HTTP_OK, context: ['groups' => 'show_product']);
    }

    #[Route('/products/shopping-list', name: 'products.shopping-list', methods: ['get'], format: 'json')]
    #[IsGranted('ROLE_USER')]
    public function shoppingList(
        #[MapQueryParameter] ?bool $count = false,
    ): JsonResponse {
        $products = $this->productRepository->findByOwnerWithAndOrderedByShoppingList($this->getCurrentUser(), $count);

        return $this->json($products, Response::HTTP_OK, context: ['groups' => 'show_product']);
    }

    #[Route('/products/{id}', name: 'products.show', methods: ['get'], format: 'json')]
    #[IsGranted('view', 'product')]
    public function show(Product $product): JsonResponse
    {
        return $this->json($product, Response::HTTP_OK, context: ['groups' => 'show_product']);
    }

    #[Route('/scanned-products/{id}', name: 'scanned.products.edit', methods: ['patch'], format: 'json')]
    #[IsGranted('edit', 'product')]
    public function scannedEdit(ScannedProduct $product, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $product = $serializer->deserialize($request->getContent(), ScannedProduct::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $product,
            AbstractNormalizer::GROUPS => 'edit_product',
        ]);

        if ($errors = $this->validate($product, ['edit'])) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->entityManager->flush();

        return $this->json($product, Response::HTTP_OK, context: ['groups' => 'show_product']);
    }

    #[Route('/custom-products/{id}', name: 'custom.products.edit', methods: ['patch'], format: 'json')]
    #[IsGranted('edit', 'product')]
    public function customEdit(CustomProduct $product, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $product = $serializer->deserialize($request->getContent(), CustomProduct::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $product,
            AbstractNormalizer::GROUPS => 'edit_product',
        ]);

        if ($errors = $this->validate($product, ['edit'])) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->entityManager->flush();

        return $this->json($product, Response::HTTP_OK, context: ['groups' => 'show_product']);
    }

    #[Route('/products/{id}', name: 'products.delete', methods: ['delete'], format: 'json')]
    #[IsGranted('edit', 'product')]
    public function delete(Product $product): JsonResponse
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
