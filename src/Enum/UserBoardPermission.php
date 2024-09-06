<?php

declare(strict_types=1);

namespace App\Enum;

enum UserBoardPermission: string
{
    case Owner = 'owner';
    case Member = 'member';
    case Viewer = 'viewer';

    public function canManage(): bool
    {
        return $this === self::Owner;
    }

    public function canEdit(): bool
    {
        return $this !== self::Viewer;
    }
}
