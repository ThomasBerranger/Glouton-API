<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseController extends AbstractController
{
    public function getCurrentUser(): User
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new \LogicException('The user is not authenticated or is not a valid User entity.');
        }

        return $user;
    }
}
