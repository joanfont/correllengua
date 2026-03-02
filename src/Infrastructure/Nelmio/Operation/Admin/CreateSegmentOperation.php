<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Operation\Admin;

use App\Infrastructure\Symfony\Http\DTO\Admin\Request\CreateSegmentRequest;
use App\Infrastructure\Symfony\Http\DTO\Common\ErrorResponse;
use Attribute;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_METHOD)]
final class CreateSegmentOperation extends OA\Post
{
    public function __construct()
    {
        parent::__construct(
            path: '/admin/segments',
            description: 'Creates a new segment under an existing itinerary.',
            summary: 'Create a segment (Admin)',
            security: [['basicAuth' => []]],
            requestBody: new OA\RequestBody(
                required: true,
                content: new OA\JsonContent(ref: new Model(type: CreateSegmentRequest::class)),
            ),
            tags: ['Admin'],
            responses: [
                new OA\Response(response: 201, description: 'Segment created successfully'),
                new OA\Response(
                    response: 401,
                    description: 'Unauthorized',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponse::class)),
                ),
                new OA\Response(
                    response: 404,
                    description: 'Itinerary not found',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponse::class)),
                ),
                new OA\Response(
                    response: 422,
                    description: 'Validation error',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponse::class)),
                ),
            ],
        );
    }
}
