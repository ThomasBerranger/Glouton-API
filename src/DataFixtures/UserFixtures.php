<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Tests\User as UserEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class UserFixtures extends Fixture
{
    public const ADMIN_REFERENCE = 'admin';

    public function __construct(private readonly PasswordHasherFactoryInterface $passwordHasherFactory)
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (UserEnum::cases() as $userEnum) {
            $user = new User();

            $user
                ->setEmail($userEnum->getEmail())
                ->setRoles($userEnum->getRole())
                ->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash($userEnum->getPlainPassword()))
                ->createToken();

            $manager->persist($user);

            if ($user->getEmail() === UserEnum::ADMIN->getEmail()) {
                $this->addReference(self::ADMIN_REFERENCE, $user);
            }
        }

        $manager->flush();
    }
}
