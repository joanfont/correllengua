<?php

namespace App\Application\Command\Registration;

use App\Application\Command\Registration\DTO\Participant;
use App\Application\Commons\Command\Command;
use App\Domain\Model\Route\Modality;
use App\Infrastructure\Symfony\Validator\Constraints\MaxSegmentsCount;
use Symfony\Component\Validator\Constraints as Assert;

readonly class RegisterParticipant implements Command
{
    public function __construct(
        #[Assert\Valid]
        public Participant $participant,
        #[MaxSegmentsCount]
        #[Assert\All([
            new Assert\Uuid(),
        ])]
        public array $segments,
        #[Assert\Choice(callback: [Modality::class, 'values'])]
        public string $modality,
    ) {
    }
}
