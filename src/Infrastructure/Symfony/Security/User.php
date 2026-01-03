<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Security;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Webmozart\Assert\Assert;

readonly class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @param array<int, string> $roles
     */
    public function __construct(
        private string $email,
        private string $password,
        private array $roles,
    ) {
        Assert::notEmpty($this->email);
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
