<?php

namespace App\Infrastructure\Doctrine\Provider\Route;

use App\Domain\DTO\Coordinates;
use App\Domain\DTO\Route\Segment;
use App\Domain\Model\Route\Segment as SegmentEntity;

class SegmentFactory
{
    public function fromEntity(SegmentEntity $segment): Segment
    {
        return new Segment(
            (string) $segment->id(),
            new Coordinates($segment->start()->latitude(), $segment->start()->longitude()),
            new Coordinates($segment->end()->latitude(), $segment->end()->longitude()),
            $segment->capacity(),
            $segment->modality()->value
        );
    }
}