<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Board;
 use App\Entity\User;
use App\Entity\UserBoard;
use App\Enum\UserBoardPermission;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Uuid;

final class BoardRepository
{
    /** @var list<Board> */
    private array $boards;

    public function __construct(private readonly Security $security)
    {
        $this->boards = [
            new Board(new Uuid('01fa956e-66c0-4a94-990b-614b27e1b183'), 'No users board', []),
            new Board(new Uuid('4fa6b614-a499-420f-bc29-28627c1b99cf'), 'Admin only board', [
                new UserBoard(new Uuid('36278cba-d8d8-484c-8efc-ddbe7dce8c7e'), new Uuid(UserRepository::USER_ADMIN_ID), UserBoardPermission::Owner),
            ]),
            new Board(new Uuid('85768014-c01c-484f-b110-6637860f19a0'), 'Admin + User 1 member', [
                new UserBoard(new Uuid('c972b0b9-9940-403c-ad15-ec93971e802b'), new Uuid(UserRepository::USER_ADMIN_ID), UserBoardPermission::Owner),
                new UserBoard(new Uuid('73ba4813-334c-4687-b2dc-2d4d2231efbd'), new Uuid(UserRepository::USER_USER1_ID), UserBoardPermission::Member),
            ]),
            new Board(new Uuid('3e1f4999-0d15-4757-981d-fe4de19a84bb'), 'Admin + User 1 member +  User 2 viewer', [
                new UserBoard(new Uuid('4b015777-53d3-4373-91c2-e3bff2a849bf'), new Uuid(UserRepository::USER_ADMIN_ID), UserBoardPermission::Owner),
                new UserBoard(new Uuid('ef8c66fb-640b-4323-9877-98193085a0c0'), new Uuid(UserRepository::USER_USER1_ID), UserBoardPermission::Member),
                new UserBoard(new Uuid('d10e4e75-9e5c-4802-b4d1-ae43c61ab703'), new Uuid(UserRepository::USER_USER2_ID), UserBoardPermission::Viewer),
            ]),
        ];
    }

    public function findAll(): array
    {
        /** @var User|null $user */
        $user = $this->security->getUser();
        if (null === $user) {
            return [];
        }

        return array_filter($this->boards, function (Board $board) use ($user) {
            foreach ($board->users as $userBoard) {
                if ($userBoard->userId->equals($user->getId())) {
                    return true;
                }
            }

            return false;
        });
    }

    public function findById(Uuid $id): ?Board
    {
        foreach ($this->boards as $board) {
            if ($board->id->equals($id)) {
                return $board;
            }
        }

        return null;
    }
}
