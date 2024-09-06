<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UserProvider implements UserProviderInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->userRepository->findByEmail($identifier) ?? throw new UserNotFoundException();
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }
}
