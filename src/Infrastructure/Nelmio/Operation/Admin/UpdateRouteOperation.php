<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Operation\Admin;

use App\Infrastructure\Nelmio\Schema\Admin\Request\UpdateRouteRequestSchema;
use App\Infrastructure\Nelmio\Schema\Common\ErrorResponseSchema;
use Attribute;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_METHOD)]
final class UpdateRouteOperation extends OA\Put
{
    public function __construct()
    {
        parent::__construct(
            path: '/admin/routes/{id}',
            description: 'Updates an existing route.',
            summary: 'Update a route (Admin)',
            security: [['basicAuth' => []]],
            requestBody: new OA\RequestBody(
                required: true,
                content: new OA\JsonContent(ref: new Model(type: UpdateRouteRequestSchema::class)),
            ),
            tags: ['Admin'],
            parameters: [
                new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
            ],
            responses: [
                new OA\Response(response: 204, description: 'Route updated successfully'),
                new OA\Response(
                    response: 401,
                    description: 'Unauthorized',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponseSchema::class)),
                ),
                new OA\Response(
                    response: 404,
                    description: 'Route not found',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponseSchema::class)),
                ),
                new OA\Response(
                    response: 422,
                    description: 'Validation error',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponseSchema::class)),
                ),
            ],
        );
    }
}
