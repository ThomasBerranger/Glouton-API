<?php

namespace App\Controller;

use App\DTO\RegistrationDTO;
use App\Entity\Token;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class SecurityController extends AbstractController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface      $entityManager,
    ) {
    }

    #[Route('/login', name: 'login', methods: ['post'])]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return $this->json([
                'message' => 'Missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json(['token' => (string) $user->getToken()]);
    }

    #[Route('/register', name: 'register', methods: ['post'])]
    public function register(#[MapRequestPayload] RegistrationDTO $registrationDTO): JsonResponse
    {
        $user = new User();

        $user->setEmail($registrationDTO->getEmail());
        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            $registrationDTO->getPassword()
        ));

        $user->setToken(new Token());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json(['token' => (string) $user->getToken()], Response::HTTP_CREATED);
    }
}
