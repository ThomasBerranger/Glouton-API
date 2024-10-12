<?php

namespace App\Security;

use App\Entity\Token;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

readonly class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        /** @var Token $accessToken */
        $accessToken = $this->entityManager->getRepository(Token::class)->find($accessToken);

        if (null === $accessToken || !$accessToken->isValid()) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        return new UserBadge($accessToken->getOwner()->getUserIdentifier());
    }
}
