<?php

namespace App\Tests\Application\Product;

use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\ExpirationDate;
use App\Entity\Product\CustomProduct;
use App\Entity\Product\ScannedProduct;
use App\Tests\BaseTest;
use App\Tests\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class IndexTest extends BaseTest
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->login($this->client, User::USER);
    }

    /**
     * @throws TransportExceptionInterface|\JsonException
     */
    public function testProductIndexShowProductsOrderedByClosestExpirationDate(): void
    {
        $firstProduct = new CustomProduct();
        $firstProduct
            ->setOwner($this->getLoggedUser())
            ->setName('First product name')
            ->setDescription('First product description')
            ->setImage('http://first-product-image-url')
            ->setFinishedAt(new \DateTime('2024-10-10 15:16:00'))
            ->addExpirationDate((new ExpirationDate())->setDate(new \DateTime('01-02-2025')))
            ->addExpirationDate((new ExpirationDate())->setDate(new \DateTime('02-02-2025')));

        $secondProduct = new ScannedProduct();
        $secondProduct
            ->setOwner($this->getLoggedUser())
            ->setName('Second product name')
            ->setDescription('Second product description')
            ->setImage('http://second-product-image-url')
            ->setFinishedAt(new \DateTime('2024-11-01 10:30:00'))
            ->setAddedToListAt(new \DateTime('2024-11-01 15:00:00'))
            ->setBarcode('123')
            ->setNutriscore('C')
            ->setEcoscore(2)
            ->setNovagroup(4)
            ->addExpirationDate((new ExpirationDate())->setDate(new \DateTime('01-01-2025')));

        static::persistAndFlush($firstProduct, $secondProduct);

        $this->client->request('GET', '/products');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonEquals([
            [
                'id' => $secondProduct->getId(),
                'name' => $secondProduct->getName(),
                'description' => $secondProduct->getDescription(),
                'image' => $secondProduct->getImage(),
                'finishedAt' => $secondProduct->getFinishedAt()->format('Y-m-d\TH:i:sP'),
                'addedToListAt' => $secondProduct->getAddedToListAt()->format('Y-m-d\TH:i:sP'),
                'nutriscore' => $secondProduct->getNutriscore(),
                'ecoscore' => $secondProduct->getEcoscore(),
                'novagroup' => $secondProduct->getNovagroup(),
                'expirationDates' => $secondProduct->getExpirationDates()->map(function (ExpirationDate $expirationDate) {
                    return ['date' => $expirationDate->getDate()->format('Y-m-d\TH:i:sP')];
                })->toArray(),
                'closestExpirationDate' => $secondProduct->getClosestExpirationDate()->format('Y-m-d\TH:i:sP'),
            ],
            [
                'id' => $firstProduct->getId(),
                'name' => $firstProduct->getName(),
                'description' => $firstProduct->getDescription(),
                'image' => $firstProduct->getImage(),
                'finishedAt' => $firstProduct->getFinishedAt()->format('Y-m-d\TH:i:sP'),
                'addedToListAt' => $firstProduct->getAddedToListAt(),
                'expirationDates' => $firstProduct->getExpirationDates()->map(function (ExpirationDate $expirationDate) {
                    return ['date' => $expirationDate->getDate()->format('Y-m-d\TH:i:sP')];
                })->toArray(),
                'closestExpirationDate' => $firstProduct->getClosestExpirationDate()->format('Y-m-d\TH:i:sP'),
            ],
        ]);
    }

    /**
     * @throws ExceptionInterface
     * @throws \JsonException
     */
    public function testProductIndexDoNotShowProductsWithoutExpirationDate(): void
    {
        $firstProduct = new CustomProduct();
        $firstProduct
            ->setOwner($this->getLoggedUser())
            ->setName('First product name');

        static::persistAndFlush($firstProduct);

        $this->client->request('GET', '/products');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonEquals([]);
    }

    /**
     * @throws ExceptionInterface
     *
     * @dataProvider requestIndexParamsProvider
     */
    public function testProductIndexWithLimitAndOrder(int $limit, int $offset, int $expectedCount): void
    {
        $firstProduct = new CustomProduct();
        $firstProduct
            ->setOwner($this->getLoggedUser())
            ->setName('First product name')
            ->addExpirationDate((new ExpirationDate())->setDate(new \DateTime('01-02-2025')));

        $secondProduct = new ScannedProduct();
        $secondProduct
            ->setOwner($this->getLoggedUser())
            ->setName('Second product name')
            ->setBarcode('123')
            ->addExpirationDate((new ExpirationDate())->setDate(new \DateTime('02-01-2025')));

        $thirdProduct = new CustomProduct();
        $thirdProduct
            ->setOwner($this->getLoggedUser())
            ->setName('Third product name')
            ->addExpirationDate((new ExpirationDate())->setDate(new \DateTime('03-01-2025')));

        static::persistAndFlush($firstProduct, $secondProduct, $thirdProduct);

        $response = $this->client->request('GET', '/products?limit='.$limit.'&offset='.$offset);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertCount($expectedCount, json_decode($response->getContent()));
    }

    public function requestIndexParamsProvider(): array
    {
        return [
            ['limit' => 1, 'offset' => 0, 'expectedCount' => 1],
            ['limit' => 2, 'offset' => 0, 'expectedCount' => 2],
            ['limit' => 3, 'offset' => 0, 'expectedCount' => 3],
            ['limit' => 3, 'offset' => 1, 'expectedCount' => 2],
            ['limit' => 3, 'offset' => 2, 'expectedCount' => 1],
            ['limit' => 2, 'offset' => 1, 'expectedCount' => 2],
            ['limit' => 10, 'offset' => 2, 'expectedCount' => 1],
        ];
    }

    /**
     * @throws TransportExceptionInterface
     * @throws \JsonException
     */
    public function testProductShoppingList(): void
    {
        $firstProduct = new CustomProduct();
        $firstProduct
            ->setOwner($this->getLoggedUser())
            ->setName('First product name')
            ->setAddedToListAt(new \DateTime('2025-01-02'));

        $secondProduct = new ScannedProduct();
        $secondProduct
            ->setOwner($this->getLoggedUser())
            ->setName('Second product name')
            ->setBarcode('123')
            ->setAddedToListAt(new \DateTime('2025-01-01'));

        $thirdProduct = new CustomProduct();
        $thirdProduct
            ->setOwner($this->getLoggedUser())
            ->setName('Third product name');

        static::persistAndFlush($firstProduct, $secondProduct, $thirdProduct);

        $this->client->request('GET', '/products/shopping-list');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonEquals([
            [
                'id' => $secondProduct->getId(),
                'name' => $secondProduct->getName(),
                'description' => $secondProduct->getDescription(),
                'image' => $secondProduct->getImage(),
                'addedToListAt' => $secondProduct->getAddedToListAt()->format('Y-m-d\TH:i:sP'),
                'finishedAt' => $firstProduct->getFinishedAt(),
                'nutriscore' => $secondProduct->getNutriscore(),
                'ecoscore' => $secondProduct->getEcoscore(),
                'novagroup' => $secondProduct->getNovagroup(),
                'expirationDates' => $secondProduct->getExpirationDates()->map(function (ExpirationDate $expirationDate) {
                    return ['date' => $expirationDate->getDate()->format('Y-m-d\TH:i:sP')];
                })->toArray(),
                'closestExpirationDate' => null,
            ],
            [
                'id' => $firstProduct->getId(),
                'name' => $firstProduct->getName(),
                'description' => $firstProduct->getDescription(),
                'image' => $firstProduct->getImage(),
                'addedToListAt' => $firstProduct->getAddedToListAt()->format('Y-m-d\TH:i:sP'),
                'finishedAt' => $firstProduct->getFinishedAt(),
                'expirationDates' => $firstProduct->getExpirationDates()->map(function (ExpirationDate $expirationDate) {
                    return ['date' => $expirationDate->getDate()->format('Y-m-d\TH:i:sP')];
                })->toArray(),
                'closestExpirationDate' => null,
            ],
        ]);

        $this->client = static::createClient();
        $this->login($this->client, User::USER);

        $this->client->request('GET', '/products/shopping-list?count=true');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonEquals(2);
    }
}
