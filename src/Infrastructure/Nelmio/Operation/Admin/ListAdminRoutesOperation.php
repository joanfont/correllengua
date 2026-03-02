<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Operation\Admin;

use App\Infrastructure\Symfony\Http\DTO\Admin\Response\PaginatedRoutesResponse;
use App\Infrastructure\Symfony\Http\DTO\Common\ErrorResponse;
use Attribute;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_METHOD)]
final class ListAdminRoutesOperation extends OA\Get
{
    public function __construct()
    {
        parent::__construct(
            path: '/admin/routes',
            description: 'Returns a paginated list of routes. Can be filtered by name.',
            summary: 'List all routes (Admin)',
            security: [['basicAuth' => []]],
            tags: ['Admin'],
            parameters: [
                new OA\Parameter(
                    name: 'name',
                    description: 'Filter routes by name (partial match)',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'string'),
                ),
                new OA\Parameter(
                    name: 'limit',
                    description: 'Number of items per page',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'integer', default: 20, minimum: 1, maximum: 100),
                ),
                new OA\Parameter(
                    name: 'maxOccupancy',
                    description: 'Filter routes that have at least one segment with this occupancy percentage or higher (0–100)',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'integer', minimum: 0, maximum: 100, example: 75),
                ),
                new OA\Parameter(
                    name: 'cursor',
                    description: 'Cursor for pagination',
                    in: 'query',
                    required: false,
                    schema: new OA\Schema(type: 'string'),
                ),
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Paginated list of routes',
                    content: new OA\JsonContent(ref: new Model(type: PaginatedRoutesResponse::class)),
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
