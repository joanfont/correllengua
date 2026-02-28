<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Operation\Admin;

use App\Infrastructure\Symfony\Http\DTO\Admin\Response\PaginatedParticipantsResponse;
use App\Infrastructure\Symfony\Http\DTO\Common\ErrorResponse;
use Attribute;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_METHOD)]
final class ListParticipantsOperation extends OA\Get
{
    public function __construct()
    {
        parent::__construct(
            path: '/admin/participants',
            description: 'Returns a paginated list of all participants with their registrations. Results can be filtered by route, itinerary, or segment.',
            summary: 'List all participants (Admin)',
            security: [['basicAuth' => []]],
            tags: ['Admin'],
            parameters: [
                new OA\Parameter(
                    name: 'routeId',
                    in: 'query',
                    description: 'Filter participants by route ID',
                    required: false,
                    schema: new OA\Schema(type: 'string', format: 'uuid'),
                ),
                new OA\Parameter(
                    name: 'itineraryId',
                    in: 'query',
                    description: 'Filter participants by itinerary ID',
                    required: false,
                    schema: new OA\Schema(type: 'string', format: 'uuid'),
                ),
                new OA\Parameter(
                    name: 'segmentId',
                    in: 'query',
                    description: 'Filter participants by segment ID',
                    required: false,
                    schema: new OA\Schema(type: 'string', format: 'uuid'),
                ),
                new OA\Parameter(
                    name: 'limit',
                    in: 'query',
                    description: 'Number of items per page (max 100)',
                    required: false,
                    schema: new OA\Schema(type: 'integer', default: 20, minimum: 1, maximum: 100),
                ),
                new OA\Parameter(
                    name: 'cursor',
                    in: 'query',
                    description: 'Cursor for pagination',
                    required: false,
                    schema: new OA\Schema(type: 'string'),
                ),
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Paginated list of participants retrieved successfully',
                    content: new OA\JsonContent(ref: new Model(type: PaginatedParticipantsResponse::class)),
                ),
                new OA\Response(
                    response: 401,
                    description: 'Unauthorized - Authentication required',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponse::class)),
                ),
                new OA\Response(
                    response: 400,
                    description: 'Bad Request - Invalid parameters',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponse::class)),
                ),
                new OA\Response(
                    response: 500,
                    description: 'Internal server error',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponse::class)),
                ),
            ],
        );
    }
}
