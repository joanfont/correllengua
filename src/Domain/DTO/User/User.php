<?php

declare(strict_types=1);

namespace App\Domain\DTO\User;

readonly class User
{
    public function __construct(
        public string $id,
    ) {
    }
}
