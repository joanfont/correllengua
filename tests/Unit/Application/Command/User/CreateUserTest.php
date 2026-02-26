<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\User;

use App\Application\Command\User\CreateUser;
use App\Application\Service\Auth\PasswordHasher;
use App\Domain\Model\User\User;
use App\Domain\Repository\User\UserRepository;
use App\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;

class CreateUserTest extends TestCase
{
    private readonly UserRepository&MockObject $userRepository;

    private readonly PasswordHasher&MockObject $passwordHasher;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->passwordHasher = $this->createMock(PasswordHasher::class);

        self::set(UserRepository::class, $this->userRepository);
        self::set(PasswordHasher::class, $this->passwordHasher);
    }

    public function testCreatesUserWithHashedPassword(): void
    {
        $email = 'user@example.com';
        $password = 'plain-password';
        $hashedPassword = 'hashed-password';

        $this->passwordHasher
            ->expects($this->once())
            ->method('hash')
            ->with($password)
            ->willReturn($hashedPassword);

        $this->userRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(fn (User $user): bool => Uuid::isValid((string) $user->id())
                && $email === $user->email()
                && $hashedPassword === $user->password()));

        $createUser = new CreateUser(
            email: $email,
            password: $password,
        );

        self::handleCommand($createUser);
    }
}
