<?php

namespace App\Tests\Application\Product;

use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Product\CustomProduct;
use App\Entity\Product\ScannedProduct;
use App\Tests\BaseTest;
use App\Tests\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class ShowTest extends BaseTest
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->login($this->client, User::USER);
    }

    /** @throws ExceptionInterface */
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

        $this->client->request('GET', '/products/'.$product->getId());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertJsonEquals([
            'id' => $product->getId(),
            'name' => 'Product name',
            'description' => 'Product description',
            'image' => 'https://product-image-url',
            'finishedAt' => '2024-10-10T15:16:00+00:00',
            'addedToListAt' => '2024-10-10T15:16:00+00:00',
            'nutriscore' => 'A',
            'ecoscore' => 1,
            'novagroup' => 4,
            'expirationDates' => [],
        ]);
    }

    /** @throws ExceptionInterface */
    public function testCustomProductShow(): void
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

        $this->client->request('GET', '/products/'.$product->getId());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertJsonEquals([
            'id' => $product->getId(),
            'name' => 'Product name',
            'description' => 'Product description',
            'image' => 'https://product-image-url',
            'finishedAt' => '2024-10-10T15:16:00+00:00',
            'addedToListAt' => '2024-10-10T15:16:00+00:00',
            'expirationDates' => [],
        ]);
    }
}
