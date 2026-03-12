<?php

declare(strict_types=1);

namespace App\Domain\Provider\Registration;

use App\Domain\DTO\Registration\Registration;

interface RegistrationProvider
{
    public function findById(string $id): Registration;

    /**
     * @return array<Registration>
     */
    public function findByParticipantId(string $participantId): array;
}
