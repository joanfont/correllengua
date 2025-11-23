<?php

namespace App\Domain\Exception\Registration;

use App\Domain\Exception\NotFoundException;
use App\Domain\Model\Registration\RegistrationId;

final class RegistrationNotFoundException extends NotFoundException
{
    public static function fromId(RegistrationId $id): self
    {
        return new self(sprintf('Registration with id = %s not found.', $id));
    }

    public static function fromHash(string $hash): self
    {
        return new self(sprintf('Registration with hash = %s not found.', $hash));
    }
}
