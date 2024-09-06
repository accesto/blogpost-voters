<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class Board
{
    public function __construct(
        public readonly Uuid $id,
        public string $title,
        /** @param list<UserBoard> $users */
        public iterable $users,
    ) {}
}
