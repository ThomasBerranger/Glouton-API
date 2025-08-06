<?php

namespace App\Tests\Application\Product;

use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Product\Category;
use App\Tests\BaseTest;
use App\Tests\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class CreateTest extends BaseTest
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
    public function testScannedProductCreateSuccess(): void
    {
        $category = $this->entityManager->getRepository(Category::class)->findOneBy([]);

        $payload = [
            'name' => 'Product name',
            'description' => 'Product description',
            'image' => 'http://product-image-url',
            'finishedAt' => '2024-10-15T15:16:17+00:00',
            'addedToListAt' => '2024-10-14T15:16:17+00:00',
            'category' => $category->getId(),
            'barcode' => '123',
            'nutriscore' => 'a',
            'novagroup' => 2,
            'ecoscore' => 'e',
            'expirationDates' => [
                ['date' => '2024-10-15T15:16:17+00:00'],
            ],
            'recipes' => [],
        ];

        $response = $this->client->request('POST', '/scanned-products', ['json' => $payload]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $payload['id'] = json_decode($response->getContent(), true)['id'];
        $payload['closestExpirationDate'] = $payload['expirationDates'][0]['date'];
        $payload['scanned'] = true;
        $payload['category'] = ['id' => $category->getId(), 'name' => $category->getName()];

        $this->assertJsonEquals($payload);
    }

    /**
     * @throws ExceptionInterface
     * @throws \JsonException
     */
    public function testCustomProductCreateSuccess(): void
    {
        $category = $this->entityManager->getRepository(Category::class)->findOneBy([]);

        $payload = [
            'name' => 'Product name',
            'description' => 'Product description',
            'image' => 'http://product-image-url',
            'finishedAt' => '2024-10-15T15:16:17+00:00',
            'addedToListAt' => '2024-10-14T15:16:17+00:00',
            'category' => $category->getId(),
            'recipes' => [],
            'expirationDates' => [
                ['date' => '2024-10-15T15:16:17+00:00'],
            ],
        ];

        $response = $this->client->request('POST', '/custom-products', ['json' => $payload]);

        $payload['id'] = json_decode($response->getContent(), true)['id'];
        $payload['closestExpirationDate'] = $payload['expirationDates'][0]['date'];
        $payload['scanned'] = false;
        $payload['category'] = ['id' => $category->getId(), 'name' => $category->getName()];

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJsonEquals($payload);
    }

    /** @throws ExceptionInterface
     *
     * @dataProvider requestCreateParamsProvider
     * */
    public function testScannedProductCreateFail(array $requiredProperties): void
    {
        $payload = [
            'name' => 'Product name',
            'description' => 'Product description',
            'image' => 'http://product-image-url',
            'finishedAt' => '2024-10-15T15:16:17+00:00',
            'addedToListAt' => '2024-10-14T15:16:17+00:00',
            'expirationDates' => [
                ['date' => '2024-10-15T15:16:17+00:00'],
            ],
        ];

        foreach ($requiredProperties as $requiredProperty) {
            $this->setUp();

            unset($payload[$requiredProperty]);

            $this->client->request('POST', '/custom-products', ['json' => $payload]);

            $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function requestCreateParamsProvider(): array
    {
        return [
            ['required_properties' => ['name', 'barcode']],
        ];
    }

    /** @throws ExceptionInterface */
    public function testCustomProductCreateFail(): void
    {
        $payload = [
            //            'name' => 'Product name',
            'description' => 'Product description',
            'image' => 'http://product-image-url',
            'finishedAt' => '2024-10-15T15:16:17+00:00',
            'addedToListAt' => '2024-10-14T15:16:17+00:00',
            'expirationDates' => [
                ['date' => '2024-10-15T15:16:17+00:00'],
            ],
        ];

        $this->client->request('POST', '/custom-products', ['json' => $payload]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
