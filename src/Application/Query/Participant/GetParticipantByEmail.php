<?php

namespace App\Application\Query\Participant;

use App\Application\Commons\Query\Query;
use App\Domain\DTO\Participant\Participant;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @implements Query<Participant>
 */
readonly class GetParticipantByEmail implements Query
{
    public function __construct(
        #[Assert\Email]
        public string $email,
    ) {
    }
}
