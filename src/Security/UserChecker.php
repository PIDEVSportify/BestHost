<?php

namespace App\Security;

use App\Entity\User;
use App\Exception\AccountDeletedException;

use http\Client\Response;

use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }



    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }
        if ($user->isBanned()) {
            throw new DisabledException("Account Banned");
        }
    }
}