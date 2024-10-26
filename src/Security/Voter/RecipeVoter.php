<?php

namespace App\Security\Voter;

use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class RecipeVoter extends Voter
{
    public const EDIT = 'edit';
    public const VIEW = 'view';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW]) && $subject instanceof Recipe;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Recipe $recipe */
        $recipe = $subject;

        return match ($attribute) {
            self::VIEW, self::EDIT => $this->canEdit($recipe, $user),
            default => throw new \LogicException('This code should not be reached!'),
        };
    }

    private function canEdit(Recipe $recipe, User $user): bool
    {
        return $user === $recipe->getOwner();
    }
}
