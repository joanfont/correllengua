<?php

declare(strict_types=1);

namespace App\Domain\Exception\User;

use App\Domain\Exception\NotFoundException;
use App\Domain\Model\User\UserId;

use function sprintf;

final class UserNotFoundException extends NotFoundException
{
    public static function fromId(UserId $id): self
    {
        return new self(sprintf('User with id = %s not found', $id));
    }

    public static function fromEmail(string $email): self
    {
        return new self(sprintf('User with email = %s not found', $email));
    }
}
