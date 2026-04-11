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

    public function __invoke(UpdateSegment $command): void
    {
        $segment = $this->segmentRepository->findById(SegmentId::from($command->id));

        $segment->update(
            position: $command->position,
            start: new Coordinates($command->startLatitude, $command->startLongitude),
            end: new Coordinates($command->endLatitude, $command->endLongitude),
            capacity: $command->capacity,
            modality: Modality::from($command->modality),
            startTime: DateTimeImmutable::createFromFormat('H:i', $command->startTime) ?: new DateTimeImmutable(),
        );
    }
}
