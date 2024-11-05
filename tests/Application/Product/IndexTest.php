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

class IndexTest extends BaseTest
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->login($this->client, User::USER);
    }

    /** @throws ExceptionInterface */
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
            ->addExpirationDate((new ExpirationDate())->setDate(new \DateTime('02-01-2025')));

        static::persistAndFlush($firstProduct, $secondProduct);

        $this->client->request('GET', '/products');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains([
            [
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
            ],
            [
                'name' => $firstProduct->getName(),
                'description' => $firstProduct->getDescription(),
                'image' => $firstProduct->getImage(),
                'finishedAt' => $firstProduct->getFinishedAt()->format('Y-m-d\TH:i:sP'),
                'addedToListAt' => $firstProduct->getAddedToListAt(),
                'expirationDates' => $firstProduct->getExpirationDates()->map(function (ExpirationDate $expirationDate) {
                    return ['date' => $expirationDate->getDate()->format('Y-m-d\TH:i:sP')];
                })->toArray(),
            ],
        ]);
    }

    /** @throws ExceptionInterface */
    public function testProductIndexDoNotShowProductsWithoutExpirationDate(): void
    {
        $firstProduct = new CustomProduct();
        $firstProduct
            ->setOwner($this->getLoggedUser())
            ->setName('First product name');

        static::persistAndFlush($firstProduct);

        $this->client->request('GET', '/products');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonContains([]);
    }
}
