<?php

declare(strict_types=1);

namespace App\Domain\Provider\Registration;

use App\Domain\DTO\Registration\Registration;
use DateTimeInterface;

interface RegistrationProvider
{
    public function findById(string $id): Registration;

    /**
     * @param array<string> $segmentIds
     *
     * @return array<Registration>
     */
    public function findByParticipantIdAndSegmentIds(string $participantId, array $segmentIds): array;

    /**
     * @return array<array<Registration>>
     */
    public function findGroupedByParticipantForRouteDate(DateTimeInterface $date): array;
}
