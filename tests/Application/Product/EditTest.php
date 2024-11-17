<?php

namespace App\Tests\Application\Product;

use ApiPlatform\Symfony\Bundle\Test\Client;
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
        $this->client = static::createClient();

        $this->login($this->client, User::USER);
    }

    /** @throws ExceptionInterface
     * @throws \JsonException
     */
    public function testScannedProductShow(): void
    {
        $product = new ScannedProduct();
        $product
            ->setName('Product name')
            ->setOwner($this->getLoggedUser())
            ->setDescription('Product description')
            ->setImage('https://product-image-url')
            ->setFinishedAt(new \DateTime('2024-10-10 15:16:00'))
            ->setAddedToListAt(new \DateTime('2024-10-10 15:16:00'))
            ->setBarcode('123')
            ->setNutriscore('A')
            ->setEcoscore(1)
            ->setNovagroup(4);

        static::persistAndFlush($product);

        $payload = [
            'name' => 'New product name',
            'description' => 'New product description',
            'image' => 'https://new-product-image-url',
            'finishedAt' => '2025-01-01T00:00:00+00:00',
            'addedToListAt' => '2025-01-02T00:00:00+00:00',
            'nutriscore' => 'B',
            'ecoscore' => 3,
            'novagroup' => 2,
            'expirationDates' => [
                ['date' => '2025-01-02T00:00:00+00:00'],
            ],
        ];

        $this->client->request('PATCH', '/scanned-products/'.$product->getId(), ['json' => $payload]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $payload['id'] = $product->getId();

        $this->assertJsonEquals($payload);
    }

    /** @throws ExceptionInterface
     * @throws \JsonException
     */
    public function testCustomProductEdit(): void
    {
        $product = new CustomProduct();
        $product
            ->setName('Product name')
            ->setOwner($this->getLoggedUser())
            ->setDescription('Product description')
            ->setImage('https://product-image-url')
            ->setFinishedAt(new \DateTime('2024-10-10 15:16:00'))
            ->setAddedToListAt(new \DateTime('2024-10-10 15:16:00'));

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

        $this->assertJsonEquals($payload);
    }
}
