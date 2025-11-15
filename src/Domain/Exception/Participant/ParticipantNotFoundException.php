<?php

namespace App\Domain\Exception\Participant;

use App\Domain\Exception\NotFoundException;
use App\Domain\Model\Participant\ParticipantId;

final class ParticipantNotFoundException extends NotFoundException
{
    public static function fromId(ParticipantId $id): self
    {
        return new self(sprintf('Participant with id = %s not found.', $id));
    }

    public static function fromEmail(string $email): self
    {
        return new self(sprintf('Participant with email = %s not found.', $email));
    }
}