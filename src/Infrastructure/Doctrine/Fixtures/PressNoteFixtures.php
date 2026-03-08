<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Fixtures;

use App\Domain\Model\File\File;
use App\Domain\Model\File\FileId;
use App\Domain\Model\Press\PressNote;
use App\Domain\Model\Press\PressNoteId;
use App\Domain\Model\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PressNoteFixtures extends Fixture implements DependentFixtureInterface
{
    public const string PRESS_NOTE_FEATURED_REFERENCE = 'press-note-featured';
    public const string PRESS_NOTE_REGULAR_REFERENCE = 'press-note-regular';
    public const string PRESS_NOTE_LINKED_REFERENCE = 'press-note-linked';

    public function load(ObjectManager $manager): void
    {
        /** @var User $admin */
        $admin = $this->getReference(UserFixtures::ADMIN_USER_REFERENCE, User::class);

        /** @var User $editor */
        $editor = $this->getReference(UserFixtures::EDITOR_USER_REFERENCE, User::class);

        $featuredImage = new File(
            id: FileId::generate(),
            name: 'correllengua-2026-banner.jpg',
            path: 'uploads/2026/correllengua-2026-banner.jpg',
        );

        $regularImage = new File(
            id: FileId::generate(),
            name: 'participants-2025.jpg',
            path: 'uploads/2025/participants-2025.jpg',
        );

        $linkedImage = new File(
            id: FileId::generate(),
            name: 'media-coverage.jpg',
            path: 'uploads/2026/media-coverage.jpg',
        );

        $manager->persist($featuredImage);
        $manager->persist($regularImage);
        $manager->persist($linkedImage);

        $featured = new PressNote(
            id: PressNoteId::generate(),
            author: $admin,
            title: 'El Correllengua 2026 ja té data!',
            subtitle: 'La gran festa de la llengua torna el 25 d\'abril',
            body: 'El Correllengua 2026 se celebrarà el proper 25 d\'abril. Enguany la ruta principal recorrerà les comarques del litoral i oferirà tres modalitats de participació: a peu, en bicicleta i mixta. Les inscripcions s\'obren el primer de març.',
            image: $featuredImage,
            featured: true,
        );

        $regular = new PressNote(
            id: PressNoteId::generate(),
            author: $editor,
            title: 'Les inscripcions superen les 500 persones',
            subtitle: 'Un èxit de participació sense precedents',
            body: 'En tan sols 48 hores d\'obertura de les inscripcions, el Correllengua 2026 ja ha superat les 500 persones apuntades. L\'organització ha ampliat la capacitat d\'alguns segments per donar cabuda a tanta participació.',
            image: $regularImage,
            featured: false,
        );

        $linked = new PressNote(
            id: PressNoteId::generate(),
            author: $admin,
            title: 'Els mitjans de comunicació es fan ressò del Correllengua',
            subtitle: 'Cobertura mediàtica a TV3, Catalunya Ràdio i la premsa escrita',
            body: 'La presentació del Correllengua 2026 ha tingut una gran cobertura mediàtica. TV3, Catalunya Ràdio i diversos diaris han dedicat espai a l\'esdeveniment.',
            image: $linkedImage,
            featured: false,
            link: 'https://www.ccma.cat/tv3/correllengua-2026',
        );

        $manager->persist($featured);
        $manager->persist($regular);
        $manager->persist($linked);
        $manager->flush();

        $this->addReference(self::PRESS_NOTE_FEATURED_REFERENCE, $featured);
        $this->addReference(self::PRESS_NOTE_REGULAR_REFERENCE, $regular);
        $this->addReference(self::PRESS_NOTE_LINKED_REFERENCE, $linked);
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
