<?php

declare(strict_types=1);

namespace App\Domain\Exception\Auth;

use App\Domain\Exception\Exception;

final class InvalidCredentialsException extends Exception
{
    public static function create(): self
    {
        return new self('Invalid credentials');
    }
}
