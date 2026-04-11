<?php

declare(strict_types=1);

namespace App\Domain\DTO\Admin\Participant;

readonly class Registration
{
    public function __construct(
        public string $id,
        public string $segmentId,
        public string $segmentName,
        public string $itineraryId,
        public string $itineraryName,
        public string $routeId,
        public string $routeName,
        public string $modality,
        public string $hash,
    ) {
    }
}
