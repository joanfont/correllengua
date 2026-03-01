<?php

declare(strict_types=1);

namespace App\Application\Query\Auth;

use App\Application\Commons\Query\Query;
use App\Domain\DTO\Auth\Token;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @implements Query<Token>
 */
readonly class IssueToken implements Query
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,
        #[Assert\NotBlank]
        public string $password,
    ) {
    }
}

