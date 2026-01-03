<?php

declare(strict_types=1);

namespace App\Domain\Repository\User;

use App\Domain\Model\User\User;

interface UserRepository
{
    public function add(User $user): void;

    public function findByEmail(string $email): User;
}
