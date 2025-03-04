<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\User as UserEnum;
use Doctrine\ORM\EntityManagerInterface;

abstract class BaseTest extends ApiTestCase
{
    private ?User $user = null;
    protected ?EntityManagerInterface $entityManager = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = self::getContainer()->get('doctrine')->getManager();
    }

    protected function getUser(UserEnum $userEnum): User
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        return $userRepository->findOneBy(['email' => $userEnum->getEmail()]);
    }

    protected function login(Client $client, UserEnum $userEnum): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $this->user = $userRepository->findOneBy(['email' => $userEnum->getEmail()]);

        $client->loginUser($this->user);
    }

    protected function getLoggedUser(): ?User
    {
        return $this->user;
    }

    protected static function persistAndFlush(object ...$objects): void
    {
        $container = static::getContainer();

        $entityManager = $container->get('doctrine.orm.entity_manager');

        foreach ($objects as $object) {
            $entityManager->persist($object);
        }

        $entityManager->flush();
    }

    protected function assertDatabaseHas(string $class, string $attribute, mixed $value): void
    {
        $result = $this->entityManager->getRepository($class)->findOneBy([$attribute => $value]);

        $this->assertNotNull($result, 'Failed asserting that the entity exists.');
    }
}
