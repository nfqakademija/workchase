<?php
namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->isConfirmed()) {
            throw new CustomUserMessageAuthenticationException('User is not confirmed');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
    }
}
