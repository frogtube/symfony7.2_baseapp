<?php

namespace App\Security\Voter;

use App\Entity\Test;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class TestVoter extends Voter
{
    public const VIEW = 'TEST_VIEW';
    public const CREATE = 'TEST_CREATE';
    public const EDIT = 'TEST_EDIT';
    public const LIST = 'TEST_LIST';
    public const LIST_ALL = 'TEST_LIST_ALL';
    public const DELETE = 'TEST_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::LIST, self::LIST_ALL, self::CREATE]) ||
            (
                in_array($attribute, [self::EDIT, self::VIEW, self::DELETE]) && $subject instanceof \App\Entity\Test
            );
    }


    /** @param Test|null $subject */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        /** @var Test $subject */
        switch ($attribute) {
            case self::EDIT:
                if ($subject->getCreatedBy()->getId() === $user->getId()) {
                    return true;
                }
                break;

            case self::DELETE:
                if ($subject->getCreatedBy()->getId() === $user->getId()) {
                    return true;
                }
                break;

            case self::LIST:
            case self::VIEW:
            case self::CREATE:
                return true;
                break;
        }

        return false;
    }
}
