<?php

namespace App\Application\Command\Participant;

use App\Application\Commons\Command\CommandHandler;
use App\Domain\Exception\Participant\ParticipantAlreadyExistsException;
use App\Domain\Model\Participant\Participant;
use App\Domain\Model\Participant\ParticipantId;
use App\Domain\Repository\Participant\ParticipantRepository;

readonly class CreateParticipantHandler implements CommandHandler
{
    public function __construct(
        private ParticipantRepository $participantRepository,
    ) {
    }

    public function __invoke(CreateParticipant $createParticipant): void
    {
        $participantExists = $this->participantRepository->existsByEmail($createParticipant->email);
        if ($participantExists) {
            throw ParticipantAlreadyExistsException::fromEmail($createParticipant->email);
        }

        $participant = new Participant(
            id: ParticipantId::generate(),
            name: $createParticipant->name,
            surname: $createParticipant->surname,
            email: $createParticipant->email,
        );

        $this->participantRepository->add($participant);
    }
}
