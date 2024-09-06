<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        public readonly Uuid $id,
        public readonly string $email,
        #[\SensitiveParameter] public string $hashedPassword,
    ) {}

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->hashedPassword;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
    }
}
