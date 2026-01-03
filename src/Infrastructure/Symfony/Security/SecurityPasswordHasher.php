<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Security;

use App\Application\Service\Auth\PasswordHasher;
use SensitiveParameter;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

readonly class SecurityPasswordHasher implements PasswordHasherInterface
{
    public function __construct(private PasswordHasher $passwordHasher)
    {
    }

    public function hash(string $plainPassword): string
    {
        return $this->passwordHasher->hash($plainPassword);
    }

    public function verify(string $hashedPassword, #[SensitiveParameter] string $plainPassword): bool
    {
        return $hashedPassword === $this->hash($plainPassword);
    }

    public function needsRehash(string $hashedPassword): bool
    {
        return false;
    }
}
