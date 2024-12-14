<?php

namespace App\Tests\Application\Security;

use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Product\CustomProduct;
use App\Entity\Product\ScannedProduct;
use App\Tests\BaseTest;
use App\Tests\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class ProductAccessTest extends BaseTest
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @throws ExceptionInterface
     *
     * @dataProvider requestCreateIndexParamsProvider
     */
    public function testCreateIndexAccess(string $method, string $url, array $options, int $expectedStatusCode): void
    {
        $this->client->request($method, $url, $options);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        $this->client = static::createClient();
        $this->login($this->client, User::USER);

        $this->client->request($method, $url, $options);

        $this->assertResponseStatusCodeSame($expectedStatusCode);
    }

    public function requestCreateIndexParamsProvider(): array
    {
        return [
            ['method' => 'POST', 'url' => '/scanned-products', 'options' => ['json' => ['name' => 'Product name', 'barcode' => '123']], 'expectedStatusCode' => Response::HTTP_CREATED],
            ['method' => 'POST', 'url' => '/custom-products', 'options' => ['json' => ['name' => 'Product name']], 'expectedStatusCode' => Response::HTTP_CREATED],
            ['method' => 'GET', 'url' => '/products', 'options' => [], 'expectedStatusCode' => Response::HTTP_OK],
        ];
    }

    /**
     * @throws ExceptionInterface
     *
     * @dataProvider requestShowDeleteParamsProvider
     */
    public function testShowDeleteAccess(string $method, string $url, array $options, int $expectedStatusCode): void
    {
        $product = new CustomProduct();
        $product
            ->setOwner($this->getUser(User::USER))
            ->setName('Product name');

        static::persistAndFlush($product);

        $this->client->request($method, $url.$product->getId());

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        $this->client = static::createClient();
        $this->login($this->client, User::USER2);

        $this->client->request($method, $url.$product->getId());

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        $this->client = static::createClient();
        $this->login($this->client, User::USER);

        $this->client->request($method, $url.$product->getId());

        $this->assertResponseStatusCodeSame($expectedStatusCode);
    }

    public function requestShowDeleteParamsProvider(): array
    {
        return [
            ['method' => 'GET', 'url' => '/products/', 'options' => [], 'expectedStatusCode' => Response::HTTP_OK],
            ['method' => 'DELETE', 'url' => '/products/', 'options' => [], 'expectedStatusCode' => Response::HTTP_NO_CONTENT],
        ];
    }

    /**
     * @throws ExceptionInterface
     */
    public function testScannedProductEditAccess(): void
    {
        $product = new ScannedProduct();
        $product
            ->setOwner($this->getUser(User::USER))
            ->setName('Product name')
            ->setBarcode('123');

        static::persistAndFlush($product);

        $this->client->request('PATCH', '/scanned-products/'.$product->getId(), ['json' => []]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        $this->client = static::createClient();
        $this->login($this->client, User::USER2);

        $this->client->request('PATCH', '/scanned-products/'.$product->getId(), ['json' => []]);

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        $this->client = static::createClient();
        $this->login($this->client, User::USER);

        $this->client->request('PATCH', '/scanned-products/'.$product->getId(), ['json' => []]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * @throws ExceptionInterface
     */
    public function testCustomProductEditAccess(): void
    {
        $product = new CustomProduct();
        $product
            ->setOwner($this->getUser(User::USER))
            ->setName('Product name');

        static::persistAndFlush($product);

        $this->client->request('PATCH', '/custom-products/'.$product->getId(), ['json' => []]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        $this->client = static::createClient();
        $this->login($this->client, User::USER2);

        $this->client->request('PATCH', '/custom-products/'.$product->getId(), ['json' => []]);

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        $this->client = static::createClient();
        $this->login($this->client, User::USER);

        $this->client->request('PATCH', '/custom-products/'.$product->getId(), ['json' => []]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
