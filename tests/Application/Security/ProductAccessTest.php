<?php

namespace App\Tests\Application\Security;

use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Product\CustomProduct;
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
    public function testProductCreateIndexAccess(string $method, string $url, array $options, int $expectedStatusCode): void
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
            ['method' => 'POST', 'url' => '/products', 'options' => ['json' => ['name' => 'Product name']], 'expectedStatusCode' => Response::HTTP_CREATED],
            ['method' => 'GET', 'url' => '/products', 'options' => [], 'expectedStatusCode' => Response::HTTP_OK],
        ];
    }

    /**
     * @throws ExceptionInterface
     *
     * @dataProvider requestShowUpdateDeleteParamsProvider
     */
    public function testProductShowUpdateDeleteAccess(string $method, string $url, array $options, int $expectedStatusCode): void
    {
        $product = new CustomProduct();
        $product
            ->setOwner($this->getUser(User::USER))
            ->setName('Product name');

        static::persistAndFlush($product);

        $this->client->request($method, $url.$product->getId(), $options);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        $this->client = static::createClient();
        $this->login($this->client, User::USER);

        $this->client->request($method, $url.$product->getId(), $options);

        $this->assertResponseStatusCodeSame($expectedStatusCode);
    }

    public function requestShowUpdateDeleteParamsProvider(): array
    {
        return [
            ['method' => 'GET', 'url' => '/products/', 'options' => [], 'expectedStatusCode' => Response::HTTP_OK],
            ['method' => 'PATCH', 'url' => '/products/', 'options' => ['json' => ['name' => 'Product name']], 'expectedStatusCode' => Response::HTTP_OK],
            ['method' => 'DELETE', 'url' => '/products/', 'options' => [], 'expectedStatusCode' => Response::HTTP_NO_CONTENT],
        ];
    }
}
