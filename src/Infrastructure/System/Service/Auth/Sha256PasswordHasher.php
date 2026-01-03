<?php

declare(strict_types=1);

namespace App\Infrastructure\System\Service\Auth;

use App\Application\Service\Auth\PasswordHasher;

use function hash;

readonly class Sha256PasswordHasher implements PasswordHasher
{
    public function __construct(private string $salt)
    {
    }

    public function hash(string $plainPassword): string
    {
        return hash('sha256', $this->salt.$plainPassword);
    }
}
