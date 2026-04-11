<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Operation\Admin;

use App\Infrastructure\Nelmio\Schema\Admin\StatsSchema;
use App\Infrastructure\Nelmio\Schema\Common\ErrorResponseSchema;
use Attribute;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_METHOD)]
final class GetStatsOperation extends OA\Get
{
    public function __construct()
    {
        parent::__construct(
            path: '/admin/stats',
            description: 'Returns enrolment statistics. Can be filtered by route, itinerary or segment.',
            summary: 'Get statistics (Admin)',
            security: [['basicAuth' => []]],
            tags: ['Admin'],
            parameters: [
                new OA\Parameter(
                    name: 'routeId',
                    description: 'Filter by route UUID',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'string', format: 'uuid'),
                ),
                new OA\Parameter(
                    name: 'itineraryId',
                    description: 'Filter by itinerary UUID',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'string', format: 'uuid'),
                ),
                new OA\Parameter(
                    name: 'segmentId',
                    description: 'Filter by segment UUID',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'string', format: 'uuid'),
                ),
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Statistics',
                    content: new OA\JsonContent(ref: new Model(type: StatsSchema::class)),
                ),
                new OA\Response(
                    response: 401,
                    description: 'Unauthorized',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponseSchema::class)),
                ),
            ],
        );
    }
}
