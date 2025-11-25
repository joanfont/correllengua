<?php

namespace App\Domain\Exception\Participant;

use App\Domain\Exception\Exception;

use function sprintf;

final class ParticipantAlreadyExistsException extends Exception
{
    public static function fromEmail(string $email): self
    {
        return new self(sprintf('Participant with email =%s already exists', $email));
    }
}
