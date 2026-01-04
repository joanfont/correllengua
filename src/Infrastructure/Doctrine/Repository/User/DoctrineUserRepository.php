<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository\User;

use App\Domain\Exception\User\UserNotFoundException;
use App\Domain\Model\User\User;
use App\Domain\Model\User\UserId;
use App\Domain\Repository\User\UserRepository;
use App\Infrastructure\Doctrine\Repository\DoctrineRepository;

class DoctrineUserRepository extends DoctrineRepository implements UserRepository
{
    public function add(User $user): void
    {
        $this->entityManager->persist($user);
    }

    public function findById(UserId $id): User
    {
        /** @var ?User $user */
        $user = $this->entityManager->find(User::class, (string) $id);

        if (null === $user) {
            throw UserNotFoundException::fromId($id);
        }

        return $user;
    }

    public function findByEmail(string $email): User
    {
        /** @var ?User $user */
        $user = $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $user) {
            throw UserNotFoundException::fromEmail($email);
        }

        return $user;
    }
}
