<?php

declare(strict_types=1);

namespace App\Application\Command\Registration\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class Participant
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
