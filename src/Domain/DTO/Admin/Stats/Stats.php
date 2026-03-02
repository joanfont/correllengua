<?php

declare(strict_types=1);

namespace App\Domain\DTO\Admin\Stats;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'enrolments', type: 'integer', example: 350),
        new OA\Property(property: 'totalCapacity', type: 'integer', example: 500, nullable: true),
        new OA\Property(
            property: 'enrolmentsPerDay',
            type: 'array',
            items: new OA\Items(ref: new Model(type: EnrolmentsPerDay::class)),
        ),
    ],
    type: 'object',
)]
readonly class Stats
{
    /**
     * @param array<EnrolmentsPerDay> $enrolmentsPerDay
     */
    public function __construct(
        public int $enrolments,
        public ?int $totalCapacity,
        public array $enrolmentsPerDay,
    ) {
    }
}
