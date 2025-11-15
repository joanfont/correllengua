<?php

namespace App\Domain\Repository\Participant;

use App\Domain\Exception\Participant\ParticipantNotFoundException;
use App\Domain\Model\Participant\Participant;
use App\Domain\Model\Participant\ParticipantId;

interface ParticipantRepository
{
    public function add(Participant $participant): void;

    /**
     * @throws ParticipantNotFoundException
     */
    public function findById(ParticipantId $id): Participant;

    public function findByEmail(string $email): Participant;

    public function existsByEmail(string $email): bool;
}
