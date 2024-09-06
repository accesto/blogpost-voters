<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Symfony\Component\Uid\Uuid;

final class UserRepository
{
    public const USER_ADMIN_ID = '6f23c70e-3048-44da-83c4-43717fbf35b8';
    public const USER_USER1_ID = '323eb0c7-cc45-44df-a8fd-33d98871cf30';
    public const USER_USER2_ID = '997d1ef5-6a19-45f6-9197-446a3f5cb8d5';

    // The password is "password"
    private const PASSWORD = '$2a$12$RGBBYz.3nYNG2WcUldJaLeLpt/pBU4OVVJICqyep/Cv5rASmTYCwS';

    /** @var $users list<User> */
    private array $users;

    public function __construct()
    {
        $this->users = [
            new User(new Uuid(self::USER_ADMIN_ID), 'admin@example.com', self::PASSWORD),
            new User(new Uuid(self::USER_USER1_ID), 'user1@example.com', self::PASSWORD),
            new User(new Uuid(self::USER_USER2_ID), 'user2@example.com', self::PASSWORD),
        ];
    }

    public function findAll(): array
    {
        return $this->users;
    }

    public function findByEmail(string $email): ?User
    {
        foreach ($this->users as $user) {
            if ($user->email === $email) {
                return $user;
            }
        }

        return null;
    }
}
