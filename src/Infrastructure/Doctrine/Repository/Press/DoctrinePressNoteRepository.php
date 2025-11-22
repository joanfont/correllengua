<?php

namespace App\Infrastructure\Doctrine\Repository\Press;

use App\Domain\Model\Press\PressNote;
use App\Domain\Repository\Press\PressNoteRepository;
use App\Infrastructure\Doctrine\Repository\DoctrineRepository;

class DoctrinePressNoteRepository extends DoctrineRepository implements PressNoteRepository
{
    public function add(PressNote $pressNote): void
    {
        $this->entityManager->persist($pressNote);
    }
}
