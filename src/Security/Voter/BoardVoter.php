<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Board;
use App\Entity\User;
use App\Enum\BoardAction;
use App\Enum\UserBoardPermission;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

final class BoardVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Board && in_array($attribute, BoardAction::values(), true);
    }

    /** @param Board $subject */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser() ?? throw new UserNotFoundException();
        $permission = $this->getUserPermissionForBoard($subject, $user);
        if (null === $permission) {
            return false;
        }

        return match (BoardAction::from($attribute)) {
            BoardAction::View => true,
            BoardAction::Modify => $permission->canEdit(),
            BoardAction::Create => $permission->canManage(),
        };
    }

    private function getUserPermissionForBoard(Board $board, User $user): ?UserBoardPermission
    {
        foreach ($board->users as $boardUser) {
            if ($boardUser->userId->equals($user->id)) {
                return $boardUser->permission;
            }
        }

        return null;
    }
}
