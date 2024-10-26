<?php

namespace App\Tests\Application\Product;

use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Product\CustomProduct;
use App\Tests\BaseTest;
use App\Tests\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class DeleteTest extends BaseTest
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->login($this->client, User::USER);
    }

    /** @throws ExceptionInterface */
    public function testProductDelete(): void
    {
        $product = new CustomProduct();
        $product
            ->setOwner($this->getLoggedUser())
            ->setName('Product name');

        static::persistAndFlush($product);

        $this->client->request('DELETE', '/products/'.$product->getId());

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
