<?php

declare(strict_types=1);

namespace App\Application\Event\Registration\ParticipantJoinedSegments;

use App\Application\Commons\Event\EventHandler;
use App\Application\Service\Notification\RegistrationCreatedNotification;
use App\Domain\Event\Registration\ParticipantJoinedSegments;
use App\Domain\Model\Route\SegmentId;
use App\Domain\Provider\Registration\RegistrationProvider;

use function array_map;

final readonly class SendEmail implements EventHandler
{
    public function __construct(
        private RegistrationProvider $registrationProvider,
        private RegistrationCreatedNotification $registrationCreatedNotification,
    ) {
    }

    public function __invoke(ParticipantJoinedSegments $participantRegistered): void
    {
        $segmentIds = array_map(
            fn (SegmentId $segmentId): string => (string) $segmentId,
            $participantRegistered->segmentIds
        );

        $registrations = $this->registrationProvider->findByParticipantIdAndSegmentIds(
            (string) $participantRegistered->participantId,
            $segmentIds
        );

        if ([] === $registrations) {
            return;
        }

        $this->registrationCreatedNotification->send($registrations);
    }
}
