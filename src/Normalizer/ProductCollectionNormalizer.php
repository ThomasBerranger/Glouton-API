<?php

namespace App\Normalizer;

use App\Entity\Product\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

readonly class ProductCollectionNormalizer implements DenormalizerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param array<string, mixed> $context
     */
    public function supportsDenormalization($data, string $type, ?string $format = null, array $context = []): bool
    {
        return 'App\Entity\Product\Product[]' === $type && is_array($data);
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return ArrayCollection<int, Product>
     */
    public function denormalize($data, string $type, ?string $format = null, array $context = []): ArrayCollection
    {
        if (!is_array($data)) {
            return new ArrayCollection();
        }

        $products = new ArrayCollection();

        foreach ($data as $uuid) {
            if (is_string($uuid)) {
                $product = $this->entityManager->getRepository(Product::class)->find($uuid);

                if ($product) {
                    $products->add($product);
                }
            }
        }

        return $products;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            'App\Entity\Product\Product[]' => true,
            '*' => false,
        ];
    }
}
