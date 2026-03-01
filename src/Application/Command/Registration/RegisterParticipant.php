<?php

declare(strict_types=1);

namespace App\Application\Command\Registration;

use App\Application\Command\Registration\DTO\Participant;
use App\Application\Commons\Command\Command;
use App\Infrastructure\Symfony\Validator\Constraints\MaxSegmentsCount;
use Symfony\Component\Validator\Constraints as Assert;

readonly class RegisterParticipant implements Command
{
    /**
     * @param array<string> $segments
     */
    public function __construct(
        #[Assert\Valid]
        public Participant $participant,
        #[MaxSegmentsCount]
        #[Assert\All([
            new Assert\Uuid(),
        ])]
        /** @var array<string> */
        public array $segments,
    ) {
    }
}
