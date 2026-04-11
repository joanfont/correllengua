<?php

declare(strict_types=1);

namespace App\Domain\DTO\Auth;

final readonly class Token
{
    public function __construct(
        public string $tokenType,
        public string $token,
    ) {
    }
}
