<?php

namespace App\Security;

use App\Entity\Product\Product;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class ProductVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        //        dd($attribute, $subject);
        return in_array($attribute, [self::VIEW, self::EDIT]) && $subject instanceof Product;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Product $product */
        $product = $subject;

        return match ($attribute) {
            self::VIEW => $this->canView($product, $user),
            self::EDIT => $this->canEdit($product, $user),
            default => throw new \LogicException('This code should not be reached!'),
        };
    }

    private function canView(Product $product, User $user): bool
    {
        return $this->canEdit($product, $user);
    }

    private function canEdit(Product $product, User $user): bool
    {
        return $user === $product->getOwner();
    }
}
