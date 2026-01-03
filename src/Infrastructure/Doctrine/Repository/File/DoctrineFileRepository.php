<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository\File;

use App\Domain\Exception\File\FileNotFoundException;
use App\Domain\Model\File\File;
use App\Domain\Model\File\FileId;
use App\Domain\Repository\File\FileRepository;
use App\Infrastructure\Doctrine\Repository\DoctrineRepository;

class DoctrineFileRepository extends DoctrineRepository implements FileRepository
{
    public function add(File $file): void
    {
        $this->entityManager->persist($file);
    }

    public function findById(FileId $id): File
    {
        $file = $this->entityManager->find(File::class, (string) $id);
        if (null === $file) {
            throw FileNotFoundException::fromId($id);
        }

        return $file;
    }
}
