<?php

namespace App\Tests\Application;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class ProductTest extends ApiTestCase
{
    /** @throws ExceptionInterface */
    public function testProductCreation(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(['email' => 'admin@gmail.com']);
        $client->loginUser($user);

        $payload = [
            'name' => 'Test Product',
        ];

        $client->request('POST', '/products', ['json' => $payload]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJsonContains([
            'name' => 'Test Product',
            'description' => null,
            'image' => null,
            'finished_at' => null,
            'added_to_list_at' => null,
        ]);
    }
}
