<?php

namespace App\Tests\Application\Product;

use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Product\Category;
use App\Entity\Product\CustomProduct;
use App\Entity\Product\ScannedProduct;
use App\Tests\BaseTest;
use App\Tests\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class EditTest extends BaseTest
{
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->login($this->client, User::USER);
    }

    /** @throws ExceptionInterface
     * @throws \JsonException
     */
    public function testScannedProductShow(): void
    {
        $entityManager = static::getContainer()->get('doctrine')->getManager();
        $category = $entityManager->getRepository(Category::class)->findOneBy([]);

        $product = new ScannedProduct();
        $product
            ->setName('Product name')
            ->setOwner($this->getLoggedUser())
            ->setDescription('Product description')
            ->setImage('https://product-image-url')
            ->setFinishedAt(new \DateTime('2024-10-10 15:16:00'))
            ->setAddedToListAt(new \DateTime('2024-10-10 15:16:00'))
            ->setBarcode('123')
            ->setNutriscore('a')
            ->setNovagroup(4)
            ->setEcoscore('b')
            ->setCategory($category);

        static::persistAndFlush($product);

        $payload = [
            'name' => 'New product name',
            'description' => 'New product description',
            'image' => 'https://new-product-image-url',
            'finishedAt' => '2025-01-01T00:00:00+00:00',
            'addedToListAt' => '2025-01-02T00:00:00+00:00',
            'nutriscore' => 'b',
            'novagroup' => 3,
            'ecoscore' => 'e',
            'recipes' => [],
            'expirationDates' => [
                ['date' => '2025-01-02T00:00:00+00:00'],
            ],
        ];

        $this->client->request('PATCH', '/scanned-products/'.$product->getId(), ['json' => $payload]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $payload['id'] = $product->getId();
        $payload['closestExpirationDate'] = $payload['expirationDates'][0]['date'];
        $payload['barcode'] = '123';
        $payload['scanned'] = true;
        $payload['category'] = ['id' => $category->getId(), 'name' => $category->getName()];

        $this->assertJsonEquals($payload);
    }

    /** @throws ExceptionInterface
     * @throws \JsonException
     */
    public function testCustomProductEdit(): void
    {
        $entityManager = static::getContainer()->get('doctrine')->getManager();
        $category = $entityManager->getRepository(Category::class)->findOneBy([]);

        $product = new CustomProduct();
        $product
            ->setName('Product name')
            ->setOwner($this->getLoggedUser())
            ->setDescription('Product description')
            ->setImage('https://product-image-url')
            ->setFinishedAt(new \DateTime('2024-10-10 15:16:00'))
            ->setAddedToListAt(new \DateTime('2024-10-10 15:16:00'))
            ->setCategory($category);

        static::persistAndFlush($product);

        $payload = [
            'name' => 'New product name',
            'description' => 'New product description',
            'image' => 'https://new-product-image-url',
            'finishedAt' => '2025-01-01T00:00:00+00:00',
            'addedToListAt' => '2025-01-02T00:00:00+00:00',
            'expirationDates' => [],
        ];

        $this->client->request('PATCH', '/custom-products/'.$product->getId(), ['json' => $payload]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $payload['id'] = $product->getId();
        $payload['closestExpirationDate'] = null;
        $payload['scanned'] = false;
        $payload['recipes'] = [];
        $payload['category'] = ['id' => $category->getId(), 'name' => $category->getName()];

        $this->assertJsonEquals($payload);
    }
}
