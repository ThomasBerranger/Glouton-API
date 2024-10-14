<?php

namespace App\Tests\Application\Product;

use App\Tests\BaseTest;
use App\Tests\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class ProductTest extends BaseTest
{
    /** @throws ExceptionInterface */
    public function testProductCreation(): void
    {
        $client = static::createClient();

        $this->login($client, User::USER);

        $payload = [
            'name' => 'Product name',
            'description' => 'Product description',
            'image' => 'http://product-image-url',
            'finished_at' => '2024-10-15 15:16:17',
            'added_to_list_at' => '2024-10-14 15:16:17',
        ];

        $client->request('POST', '/products', ['json' => $payload]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJsonContains([
            'name' => 'Product name',
            'description' => 'Product description',
            'image' => 'http://product-image-url',
            'finished_at' => '2024-10-15T15:16:17+00:00',
            'added_to_list_at' => '2024-10-14T15:16:17+00:00',
        ]);
    }
}
