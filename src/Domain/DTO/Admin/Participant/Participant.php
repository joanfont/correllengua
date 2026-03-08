<?php

declare(strict_types=1);

namespace App\Domain\DTO\Admin\Participant;

readonly class Participant
{
    /**
     * @param array<Registration> $registrations
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $surname,
        public string $email,
        public array $registrations,
    ) {
    }
}
