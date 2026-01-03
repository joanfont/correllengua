<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Security;

use App\Domain\Repository\User\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @implements UserProviderInterface<User>
 */
readonly class UserProvider implements UserProviderInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private UserFactory $userFactory,
    ) {
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userRepository->findByEmail($identifier);

        return $this->userFactory->fromEntity($user);
    }
}
