<?php

namespace App\Application\Query\Participant;

use App\Application\Commons\Query\QueryHandler;
use App\Domain\DTO\Participant\Participant;
use App\Domain\Provider\Participant\ParticipantProvider;

readonly class GetParticipantByEmailHandler implements QueryHandler
{
    public function __construct(
        private ParticipantProvider $participantProvider,
    ) {}

    public function __invoke(GetParticipantByEmail $getParticipantByEmail): Participant
    {
        return $this->participantProvider->findByEmail($getParticipantByEmail->email);
    }
}
