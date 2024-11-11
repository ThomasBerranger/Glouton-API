<?php

namespace App\Tests\Application\Security;

use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Tests\BaseTest;
use App\Tests\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class AuthenticationTest extends BaseTest
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @throws ExceptionInterface
     */
    public function testLogin(): void
    {
        $this->client->request('POST', '/login');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        $payload = [
            'username' => User::USER->getEmail(),
            'password' => User::USER->getPlainPassword(),
        ];

        $this->client->request('POST', '/login', ['json' => $payload]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJsonEquals(['token' => (string) $this->getUser(User::USER)->getToken()]);
    }

    /**
     * @throws ExceptionInterface
     */
    public function testRegister(): void
    {
        $this->client->request('POST', '/register');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->client->request('POST', '/register', ['json' => ['email' => 'new-user@gmail.com']]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->client->request('POST', '/register', ['json' => ['password' => 'password']]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);

        $payload = [
            'email' => 'new-user@gmail.com',
            'password' => 'password',
        ];

        $this->client->request('POST', '/register', ['json' => $payload]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    /**
     * @throws ExceptionInterface
     */
    public function testLogout(): void
    {
        $this->client->request('POST', '/logout');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        $this->client = static::createClient();
        $this->login($this->client, User::USER);

        $this->client->request('POST', '/logout');

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
