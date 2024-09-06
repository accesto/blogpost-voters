<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\UserBoardPermission;
use Symfony\Component\Uid\Uuid;

class UserBoard
{
    public function __construct(
        public readonly Uuid $id,
        public readonly Uuid $userId, // with Doctrine ORM here would be User instance
        public UserBoardPermission $permission,
    ) {}
}
