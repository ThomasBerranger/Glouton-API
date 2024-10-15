<?php

namespace App\Tests\Application\Product;

use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Product\CustomProduct;
use App\Tests\BaseTest;
use App\Tests\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class ProductTest extends BaseTest
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->login($this->client, User::USER);
    }

    /** @throws ExceptionInterface */
    public function testProductCreate(): void
    {
        $payload = [
            'name' => 'Product name',
            'description' => 'Product description',
            'image' => 'http://product-image-url',
            'finished_at' => '2024-10-15T15:16:17+00:00',
            'added_to_list_at' => '2024-10-14T15:16:17+00:00',
        ];

        $this->client->request('POST', '/products', ['json' => $payload]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJsonContains($payload);
    }

    /** @throws ExceptionInterface */
    public function testProductIndex(): void
    {
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

        $this->client->request('GET', '/products');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains([
            [
                'name' => $firstProduct->getName(),
                'description' => $firstProduct->getDescription(),
                'image' => $firstProduct->getImage(),
                'finished_at' => $firstProduct->getFinishedAt()->format('Y-m-d\TH:i:sP'),
                'added_to_list_at' => null,
            ],
            [
                'name' => $secondProduct->getName(),
                'description' => $secondProduct->getDescription(),
                'image' => $secondProduct->getImage(),
                'finished_at' => $secondProduct->getFinishedAt()->format('Y-m-d\TH:i:sP'),
                'added_to_list_at' => $secondProduct->getAddedToListAt()->format('Y-m-d\TH:i:sP'),
            ],
        ]);
    }

    /** @throws ExceptionInterface */
    public function testProductShow(): void
    {
        $product = new CustomProduct();
        $product
            ->setOwner($this->getLoggedUser())
            ->setName('Product name')
            ->setDescription('Product description')
            ->setImage('http://product-image-url')
            ->setFinishedAt(new \DateTime('2024-10-10 15:16:00'));

        static::persistAndFlush($product);

        $this->client->request('GET', '/products/'.$product->getId());

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains([
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'image' => $product->getImage(),
            'finished_at' => $product->getFinishedAt()->format('Y-m-d\TH:i:sP'),
            'added_to_list_at' => null,
        ]);
    }

    /** @throws ExceptionInterface */
    public function testProductEdit(): void
    {
        $product = new CustomProduct();
        $product
            ->setOwner($this->getLoggedUser())
            ->setName('Product name')
            ->setDescription('Product description');

        static::persistAndFlush($product);

        $payload = [
            'name' => 'Product new name',
            'description' => 'Product new description',
            'image' => 'http://product-new-image-url',
            'finished_at' => '2024-10-15T15:16:17+00:00',
            'added_to_list_at' => '2024-10-14T15:16:17+00:00',
        ];

        $this->client->request('PATCH', '/products/'.$product->getId(), ['json' => $payload]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains($payload);
    }

    /** @throws ExceptionInterface */
    public function testProductDelete(): void
    {
        $product = new CustomProduct();
        $product
            ->setOwner($this->getLoggedUser())
            ->setName('Product name')
            ->setDescription('Product description');

        static::persistAndFlush($product);

        $this->client->request('DELETE', '/products/'.$product->getId());

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
