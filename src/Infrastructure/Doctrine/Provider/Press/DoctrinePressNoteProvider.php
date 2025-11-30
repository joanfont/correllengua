<?php

namespace App\Infrastructure\Doctrine\Provider\Press;

use App\Domain\DTO\Press\PressNote;
use App\Domain\Model\Press\PressNote as PressNoteEntity;
use App\Domain\Provider\Press\PressNoteProvider;
use App\Infrastructure\Doctrine\Provider\DoctrineProvider;
use App\Infrastructure\Doctrine\Provider\File\FileFactory;
use Doctrine\ORM\EntityManagerInterface;

class DoctrinePressNoteProvider extends DoctrineProvider implements PressNoteProvider
{
    public function __construct(
        private readonly FileFactory $fileFactory,
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager);
    }

    /**
     * @return array<PressNote>
     */
    public function listAll(): array
    {
        $result = $this->entityManager->createQueryBuilder()
            ->select('p', 'f')
            ->from(PressNoteEntity::class, 'p')
            ->join('p.image', 'f')
            ->addOrderBy('p.featured', 'DESC')
            ->addOrderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        /** @var array<PressNoteEntity> $pressNotes */
        $pressNotes = $result;

        $mapped = [];
        foreach ($pressNotes as $pressNote) {
            $mapped[] = $this->buildPressNote($pressNote);
        }

        return $mapped;
    }

    private function buildPressNote(PressNoteEntity $pressNote): PressNote
    {
        return new PressNote(
            id: (string) $pressNote->id(),
            title: $pressNote->title(),
            subtitle: $pressNote->subtitle(),
            body: $pressNote->body(),
            featured: $pressNote->featured(),
            image: $this->fileFactory->fromEntity($pressNote->image()),
        );
    }
}
