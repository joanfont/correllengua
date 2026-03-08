<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Operation\Route;

use App\Infrastructure\Nelmio\Schema\Common\ErrorResponseSchema;
use App\Infrastructure\Nelmio\Schema\Route\RouteResponseSchema;
use Attribute;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_METHOD)]
final class ListRoutesOperation extends OA\Get
{
    public function __construct()
    {
        parent::__construct(
            path: '/route',
            description: 'Returns a list of all available routes with their itineraries and segments',
            summary: 'List all routes',
            tags: ['Routes'],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'List of routes retrieved successfully',
                    content: new OA\JsonContent(
                        type: 'array',
                        items: new OA\Items(ref: new Model(type: RouteResponseSchema::class)),
                    ),
                ),
                new OA\Response(
                    response: 500,
                    description: 'Internal server error',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponseSchema::class)),
                ),
            ],
        );
    }
}
