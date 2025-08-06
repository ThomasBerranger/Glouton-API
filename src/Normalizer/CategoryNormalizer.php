<?php

namespace App\Normalizer;

use App\Entity\Product\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

readonly class CategoryNormalizer implements DenormalizerInterface
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
        return 'App\Entity\Product\Category' === $type;
    }

    /**
     * @param array<string, mixed> $context
     */
    public function denormalize($data, string $type, ?string $format = null, array $context = []): ?Category
    {
        return $this->entityManager->getRepository(Category::class)->find($data);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            'App\Entity\Product\Category' => true,
            '*' => false,
        ];
    }
}
