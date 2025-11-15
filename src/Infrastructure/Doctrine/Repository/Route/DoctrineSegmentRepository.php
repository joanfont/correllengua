<?php

namespace App\Infrastructure\Doctrine\Repository\Route;

use App\Domain\Exception\Route\SegmentNotFoundException;
use App\Domain\Model\Route\Segment;
use App\Domain\Model\Route\SegmentId;
use App\Domain\Repository\Route\SegmentRepository;
use App\Infrastructure\Doctrine\Repository\DoctrineRepository;

class DoctrineSegmentRepository extends DoctrineRepository implements SegmentRepository
{
    public function add(Segment $segment): void
    {
        $this->entityManager->persist($segment);
    }

    public function findById(SegmentId $id): Segment
    {
        $segment = $this->entityManager->find(Segment::class, (string) $id);
        if (null === $segment) {
            throw SegmentNotFoundException::fromId($id);
        }

        return $segment;
    }
}
