<?php

namespace App\Application\Command\Registration;

use App\Application\Commons\Command\Command;
use App\Domain\Model\Route\Modality;
use Symfony\Component\Validator\Constraints as Assert;

readonly class RegisterParticipant implements Command
{
    public function __construct(
        #[Assert\Uuid]
        public string $participantId,
        #[Assert\Uuid]
        public string $segmentId,
        #[Assert\Choice(callback: [Modality::class, 'values'])]
        public string $modality,
    ) {
    }
}
