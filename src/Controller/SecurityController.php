<?php

namespace App\Controller;

use App\DTO\RegistrationDTO;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class SecurityController extends BaseController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $entityManager,
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

        $user->removeToken()->createToken();

        $this->entityManager->flush();

        return $this->json(['token' => (string) $user->getToken()]);
    }

    #[Route('/register', name: 'register', methods: ['post'])]
    public function register(#[MapRequestPayload] RegistrationDTO $registrationDTO): JsonResponse
    {
        $user = new User();

        $user
            ->setEmail($registrationDTO->getEmail())
            ->setPassword($this->passwordHasher->hashPassword(
                $user,
                $registrationDTO->getPassword()
            ))
            ->createToken();

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json(['token' => (string) $user->getToken()], Response::HTTP_CREATED);
    }

    #[Route('/logout', name: 'logout', methods: ['post'])]
    #[IsGranted('ROLE_USER')]
    public function logout(): JsonResponse
    {
        $this->getCurrentUser()->removeToken();

        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
