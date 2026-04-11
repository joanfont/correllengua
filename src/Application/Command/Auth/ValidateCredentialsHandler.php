<?php

declare(strict_types=1);

namespace App\Application\Command\Auth;

use App\Application\Commons\Command\CommandHandler;
use App\Application\Service\Auth\PasswordHasher;
use App\Domain\Exception\Auth\InvalidCredentialsException;
use App\Domain\Exception\User\UserNotFoundException;
use App\Domain\Repository\User\UserRepository;

readonly class ValidateCredentialsHandler implements CommandHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private PasswordHasher $passwordHasher,
    ) {
    }

    public function __invoke(ValidateCredentials $validateCredentials): void
    {
        try {
            $user = $this->userRepository->findByEmail($validateCredentials->email);
        } catch (UserNotFoundException) {
            throw InvalidCredentialsException::create();
        }

        if ($this->passwordHasher->hash($validateCredentials->password) !== $user->password()) {
            throw InvalidCredentialsException::create();
        }
    }
}
