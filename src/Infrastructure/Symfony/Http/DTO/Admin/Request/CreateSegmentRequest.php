<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Admin\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateSegmentRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $itineraryId,
        #[Assert\Positive]
        public int $position,
        #[Assert\NotBlank]
        #[Assert\Range(min: -90, max: 90)]
        public float $startLatitude,
        #[Assert\NotBlank]
        #[Assert\Range(min: -180, max: 180)]
        public float $startLongitude,
        #[Assert\NotBlank]
        #[Assert\Range(min: -90, max: 90)]
        public float $endLatitude,
        #[Assert\NotBlank]
        #[Assert\Range(min: -180, max: 180)]
        public float $endLongitude,
        public ?int $capacity,
        public ?int $reservedCapacity,
        #[Assert\NotBlank]
        #[Assert\Choice(choices: ['WALK', 'BIKE', 'MIXED', 'END'])]
        public string $modality,
        #[Assert\NotBlank]
        #[Assert\Regex(pattern: '/^\d{2}:\d{2}$/')]
        public string $startTime,
    ) {
    }
}
