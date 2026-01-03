<?php

declare(strict_types=1);

namespace App\Application\Command\User;

use App\Application\Commons\Command\CommandHandler;
use App\Application\Service\Auth\PasswordHasher;
use App\Domain\Model\User\User;
use App\Domain\Model\User\UserId;
use App\Domain\Repository\User\UserRepository;

readonly class CreateUserHandler implements CommandHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private PasswordHasher $passwordHasher,
    ) {
    }

    public function __invoke(CreateUser $createUser): void
    {
        $user = new User(
            id: UserId::generate(),
            email: $createUser->email,
            password: $this->passwordHasher->hash($createUser->password),
        );

        $this->userRepository->add($user);
    }
}
