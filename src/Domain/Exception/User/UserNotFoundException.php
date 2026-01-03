<?php

declare(strict_types=1);

namespace App\Domain\Exception\User;

use App\Domain\Exception\NotFoundException;

final class UserNotFoundException extends NotFoundException
{
    public static function fromEmail(string $email): self
    {
        return new self("User with email '{$email}' not found.");
    }
}
