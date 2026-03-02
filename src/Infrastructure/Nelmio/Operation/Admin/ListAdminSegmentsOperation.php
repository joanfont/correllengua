<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Operation\Admin;

use App\Infrastructure\Symfony\Http\DTO\Admin\Response\PaginatedSegmentsResponse;
use App\Infrastructure\Symfony\Http\DTO\Common\ErrorResponse;
use Attribute;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_METHOD)]
final class ListAdminSegmentsOperation extends OA\Get
{
    public function __construct()
    {
        parent::__construct(
            path: '/admin/segments',
            description: 'Returns a paginated list of segments. Can be filtered by itinerary, route and modality.',
            summary: 'List all segments (Admin)',
            security: [['basicAuth' => []]],
            tags: ['Admin'],
            parameters: [
                new OA\Parameter(
                    name: 'itineraryId',
                    description: 'Filter segments by itinerary ID',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'string', format: 'uuid'),
                ),
                new OA\Parameter(
                    name: 'routeId',
                    description: 'Filter segments by route ID',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'string', format: 'uuid'),
                ),
                new OA\Parameter(
                    name: 'modality',
                    description: 'Filter segments by modality',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'string', enum: ['WALK', 'BIKE', 'MIXED', 'END']),
                ),
                new OA\Parameter(
                    name: 'limit',
                    description: 'Number of items per page',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'integer', default: 20, minimum: 1, maximum: 100),
                ),
                new OA\Parameter(
                    name: 'cursor',
                    description: 'Cursor for pagination',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'string'),
                ),
                new OA\Parameter(
                    name: 'maxOccupancy',
                    description: 'Filter segments with this occupancy percentage or higher (0–100)',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'integer', minimum: 0, maximum: 100, example: 75),
                ),
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Paginated list of segments',
                    content: new OA\JsonContent(ref: new Model(type: PaginatedSegmentsResponse::class)),
                ),
                new OA\Response(
                    response: 401,
                    description: 'Unauthorized',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponse::class)),
                ),
            ],
        );
    }
}
