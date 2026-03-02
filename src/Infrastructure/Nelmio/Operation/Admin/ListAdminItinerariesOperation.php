<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Operation\Admin;

use App\Infrastructure\Symfony\Http\DTO\Admin\Response\PaginatedItinerariesResponse;
use App\Infrastructure\Symfony\Http\DTO\Common\ErrorResponse;
use Attribute;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_METHOD)]
final class ListAdminItinerariesOperation extends OA\Get
{
    public function __construct()
    {
        parent::__construct(
            path: '/admin/itineraries',
            description: 'Returns a paginated list of itineraries. Can be filtered by name and route.',
            summary: 'List all itineraries (Admin)',
            security: [['basicAuth' => []]],
            tags: ['Admin'],
            parameters: [
                new OA\Parameter(
                    name: 'name',
                    description: 'Filter itineraries by name (partial match)',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'string'),
                ),
                new OA\Parameter(
                    name: 'routeId',
                    description: 'Filter itineraries by route ID',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'string', format: 'uuid'),
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
                    description: 'Filter itineraries that have at least one segment with this occupancy percentage or higher (0–100)',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'integer', minimum: 0, maximum: 100, example: 75),
                ),
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Paginated list of itineraries',
                    content: new OA\JsonContent(ref: new Model(type: PaginatedItinerariesResponse::class)),
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
