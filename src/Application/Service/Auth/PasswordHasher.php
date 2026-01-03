<?php

declare(strict_types=1);

namespace App\Application\Service\Auth;

interface PasswordHasher
{
    public function hash(string $plainPassword): string;
}
