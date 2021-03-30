<?php

namespace App\Security\Voter;

use App\Entity\Forum;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ForumVoter extends Voter
{
    private const LOCK = 'LOCK';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports( $attribute, $subject): bool
    {
        return in_array($attribute, [self::LOCK], true)
            && $subject instanceof Forum;
    }

    protected function voteOnAttribute( $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::LOCK:
                return $this->canLock();
        }

        return false;
    }

    private function canLock(): bool
    {
        return $this->security->isGranted('ROLE_MODERATOR');
    }
}
