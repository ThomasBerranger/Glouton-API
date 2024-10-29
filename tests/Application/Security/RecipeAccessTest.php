<?php

namespace App\Tests\Application\Security;

use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Product\CustomProduct;
use App\Entity\Recipe;
use App\Tests\BaseTest;
use App\Tests\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class RecipeAccessTest extends BaseTest
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
    public function testRecipeCreateIndexAccess(string $method, string $url, array $options, int $expectedStatusCode): void
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
            ['method' => 'POST', 'url' => '/recipes', 'options' => ['json' => ['name' => 'Recipe name']], 'expectedStatusCode' => Response::HTTP_CREATED],
            ['method' => 'GET', 'url' => '/recipes', 'options' => [], 'expectedStatusCode' => Response::HTTP_OK],
        ];
    }

    /**
     * @throws ExceptionInterface
     *
     * @dataProvider requestShowDeleteParamsProvider
     */
    public function testRecipeShowEditDeleteAccess(string $method, string $url, array $options, int $expectedStatusCode): void
    {
        $recipe = new Recipe();
        $recipe
            ->setOwner($this->getUser(User::USER))
            ->setName('Product name');

        static::persistAndFlush($recipe);

        $this->client->request($method, '/recipes/'.$recipe->getId(), ['json' => []]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        $this->client = static::createClient();
        $this->login($this->client, User::USER2);

        $this->client->request($method, '/recipes/'.$recipe->getId(), ['json' => []]);

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        $this->client = static::createClient();
        $this->login($this->client, User::USER);

        $this->client->request($method, '/recipes/'.$recipe->getId(), ['json' => []]);

        $this->assertResponseStatusCodeSame($expectedStatusCode);
    }

    public function requestShowDeleteParamsProvider(): array
    {
        return [
            ['method' => 'GET', 'url' => '/recipes/', 'options' => [], 'expectedStatusCode' => Response::HTTP_OK],
            ['method' => 'PATCH', 'url' => '/recipes/', 'options' => [], 'expectedStatusCode' => Response::HTTP_OK],
            ['method' => 'DELETE', 'url' => '/recipes/', 'options' => [], 'expectedStatusCode' => Response::HTTP_NO_CONTENT],
        ];
    }

    /**
     * @throws ExceptionInterface
     */
    public function testRecipeToggleProductAccess(): void
    {
        $recipe = (new Recipe())->setName('Product name')->setOwner($this->getUser(User::USER));
        $product = (new CustomProduct())->setName('Recipe name')->setOwner($this->getUser(User::USER));

        static::persistAndFlush($recipe, $product);

        $this->client->request('PATCH', '/recipes/'.$recipe->getId().'/'.$product->getId());

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        $this->client = static::createClient();
        $this->login($this->client, User::USER);

        $this->client->request('PATCH', '/recipes/'.$recipe->getId().'/'.$product->getId());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
