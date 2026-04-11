<?php

declare(strict_types=1);

namespace App\Application\Command\Route\Admin;

use App\Application\Commons\Command\CommandHandler;
use App\Domain\Model\Coordinates;
use App\Domain\Model\Route\Modality;
use App\Domain\Model\Route\SegmentId;
use App\Domain\Repository\Route\SegmentRepository;
use DateTimeImmutable;

readonly class UpdateSegmentHandler implements CommandHandler
{
    public function __construct(private SegmentRepository $segmentRepository)
    {
    }

    public function __invoke(UpdateSegment $updateSegment): void
    {
        $segment = $this->segmentRepository->findById(SegmentId::from($updateSegment->id));

        $segment->update(
            position: $updateSegment->position,
            start: new Coordinates($updateSegment->startLatitude, $updateSegment->startLongitude),
            end: new Coordinates($updateSegment->endLatitude, $updateSegment->endLongitude),
            capacity: $updateSegment->capacity,
            reservedCapacity: $updateSegment->reservedCapacity,
            modality: Modality::from($updateSegment->modality),
            startTime: DateTimeImmutable::createFromFormat('H:i', $updateSegment->startTime) ?: new DateTimeImmutable(),
        );
    }
}
