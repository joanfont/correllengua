<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Operation\Admin;

use App\Infrastructure\Nelmio\Schema\Admin\Request\CreateItineraryRequestSchema;
use App\Infrastructure\Nelmio\Schema\Common\ErrorResponseSchema;
use Attribute;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_METHOD)]
final class CreateItineraryOperation extends OA\Post
{
    public function __construct()
    {
        parent::__construct(
            path: '/admin/itineraries',
            description: 'Creates a new itinerary under an existing route.',
            summary: 'Create an itinerary (Admin)',
            security: [['basicAuth' => []]],
            requestBody: new OA\RequestBody(
                required: true,
                content: new OA\JsonContent(ref: new Model(type: CreateItineraryRequestSchema::class)),
            ),
            tags: ['Admin'],
            responses: [
                new OA\Response(response: 201, description: 'Itinerary created successfully'),
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
