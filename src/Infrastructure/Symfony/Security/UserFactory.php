<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Security;

use App\Domain\Model\User\User as UserEntity;

readonly class UserFactory
{
    public function fromEntity(UserEntity $user): User
    {
        return new User(
            email: $user->email(),
            password: $user->password(),
            roles: ['ROLE_USER']
        );
    }
}
