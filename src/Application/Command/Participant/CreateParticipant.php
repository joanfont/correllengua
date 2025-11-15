<?php

namespace App\Application\Command\Participant;

use App\Application\Commons\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateParticipant implements Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 128)]
        public string $name,
        #[Assert\Length(min: 1, max: 128)]
        public string $surname,
        #[Assert\Email]
        public string $email,
    ) {
    }
}
