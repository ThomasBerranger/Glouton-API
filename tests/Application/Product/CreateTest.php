<?php

namespace App\Tests\Application\Product;

use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Tests\BaseTest;
use App\Tests\User;
use JsonException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class CreateTest extends BaseTest
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->login($this->client, User::USER);
    }

    /** @throws ExceptionInterface
     * @throws JsonException
     */
    public function testScannedProductCreateSuccess(): void
    {
        $payload = [
            'name' => 'Product name',
            'description' => 'Product description',
            'image' => 'http://product-image-url',
            'finishedAt' => '2024-10-15T15:16:17+00:00',
            'addedToListAt' => '2024-10-14T15:16:17+00:00',
            'barcode' => '123',
            'nutriscore' => 'A',
            'novagroup' => 2,
            'ecoscore' => 3,
            'expirationDates' => [
                ['date' => '2024-10-15T15:16:17+00:00'],
            ],
        ];

        $response = $this->client->request('POST', '/scanned-products', ['json' => $payload]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $payload['id'] = json_decode($response->getContent(), true)['id'];
        $payload['closestExpirationDate'] = $payload['expirationDates'][0]['date'];

        unset($payload['barcode']);

        $this->assertJsonEquals($payload);
    }

    /** @throws ExceptionInterface
     * @throws JsonException
     */
    public function testCustomProductCreateSuccess(): void
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

        $response = $this->client->request('POST', '/custom-products', ['json' => $payload]);

        $payload['id'] = json_decode($response->getContent(), true)['id'];
        $payload['closestExpirationDate'] = $payload['expirationDates'][0]['date'];

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
