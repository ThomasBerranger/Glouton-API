<?php

namespace App\Tests\Application\Product;

use App\Entity\Product\CustomProduct;
use App\Entity\Product\Product;
use App\Tests\BaseTest;
use App\Tests\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class ProductTest extends BaseTest
{
    /** @throws ExceptionInterface */
    public function testProductCreate(): void
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

    /** @throws ExceptionInterface */
    public function testProductIndex(): void
    {
        $client = static::createClient();

        $this->login($client, User::USER);

        $firstProduct = new CustomProduct();
        $firstProduct
            ->setOwner($this->getLoggedUser())
            ->setName('First product name')
            ->setDescription('First product description')
            ->setImage('http://first-product-image-url')
            ->setFinishedAt(new \DateTime('2024-10-10 15:16:00'));

        $secondProduct = new CustomProduct();
        $secondProduct
            ->setOwner($this->getLoggedUser())
            ->setName('Second product name')
            ->setDescription('Second product description')
            ->setImage('http://second-product-image-url')
            ->setFinishedAt(new \DateTime('2024-11-01 10:30:00'))
            ->setAddedToListAt(new \DateTime('2024-11-01 15:00:00'));

        static::persistAndFlush($firstProduct, $secondProduct);

        $client->request('GET', '/products');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains([
            [
                'name' => $firstProduct->getName(),
                'description' => $firstProduct->getDescription(),
                'image' => $firstProduct->getImage(),
                'finished_at' => '2024-10-10T15:16:00+00:00',
                'added_to_list_at' => null,
            ],
            [
                'name' => $secondProduct->getName(),
                'description' => $secondProduct->getDescription(),
                'image' => $secondProduct->getImage(),
                'finished_at' => '2024-11-01T10:30:00+00:00',
                'added_to_list_at' => '2024-11-01T15:00:00+00:00',
            ],
        ]);
    }
}
