<?php

namespace App\Tests\Application\EventListener;

use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Product\Product;
use App\Entity\User as UserEntity;
use App\Tests\BaseTest;
use App\Tests\User;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class ProductTest extends BaseTest
{
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->login($this->client, User::USER);
    }

    /**
     * @throws ExceptionInterface
     */
    public function testCurrentUserIsOwnerOnCreate(): void
    {
        $this->client->request('POST', '/custom-products', ['json' => ['name' => 'name']]);

        $this->entityManager->clear();

        $user = $this->entityManager->getRepository(UserEntity::class)->findOneBy(['email' => User::USER->getEmail()]);

        $this->assertDatabaseHas(Product::class, 'owner', $user->getId());
    }
}
