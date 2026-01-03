<?php

declare(strict_types=1);

namespace App\Domain\Model\User;

use App\Domain\Model\Entity;

class User extends Entity
{
    private string $id;

    public function __construct(
        UserId $id,
        private string $email,
        private string $password,
    ) {
        $this->id = (string) $id;
    }

    public function id(): UserId
    {
        return UserId::from($this->id);
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }
}
