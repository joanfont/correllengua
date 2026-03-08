<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Fixtures;

use App\Application\Service\Auth\PasswordHasher;
use App\Domain\Model\User\User;
use App\Domain\Model\User\UserId;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const string ADMIN_USER_REFERENCE = 'user-admin';
    public const string EDITOR_USER_REFERENCE = 'user-editor';

    public function __construct(private readonly PasswordHasher $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User(
            id: UserId::generate(),
            email: 'admin@correllengua.cat',
            password: $this->passwordHasher->hash('admin1234'),
        );

        $editor = new User(
            id: UserId::generate(),
            email: 'editor@correllengua.cat',
            password: $this->passwordHasher->hash('editor1234'),
        );

        $manager->persist($admin);
        $manager->persist($editor);
        $manager->flush();

        $this->addReference(self::ADMIN_USER_REFERENCE, $admin);
        $this->addReference(self::EDITOR_USER_REFERENCE, $editor);
    }
}
