<?php

declare(strict_types=1);

namespace App\Enum;

enum BoardAction: string
{
    case Create = 'create';
    case View = 'view';
    case Modify = 'modify';

    public static function values(): array
    {
        return array_map(fn (BoardAction $action) => $action->value, self::cases());
    }
}
