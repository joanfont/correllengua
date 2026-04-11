<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Operation\Press;

use App\Infrastructure\Nelmio\Schema\Common\ErrorResponseSchema;
use App\Infrastructure\Nelmio\Schema\Press\CreatePressNoteRequestSchema;
use Attribute;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_METHOD)]
final class CreatePressNoteOperation extends OA\Post
{
    public function __construct()
    {
        parent::__construct(
            path: '/press',
            description: 'Create a new press note with title, subtitle, body content, and an image. Optionally include an external link. Requires authentication via HTTP Basic Auth. The image must be a valid image file (JPEG, PNG, GIF) with a maximum size of 2MB.',
            summary: 'Create a new press note',
            security: [['basicAuth' => []]],
            requestBody: new OA\RequestBody(
                description: 'Press note data including title, subtitle, body, featured flag, image file, and optional external link',
                required: true,
                content: new OA\MediaType(
                    mediaType: 'multipart/form-data',
                    schema: new OA\Schema(ref: new Model(type: CreatePressNoteRequestSchema::class)),
                ),
            ),
            tags: ['Press'],
            responses: [
                new OA\Response(
                    response: 201,
                    description: 'Press note created successfully. The note is now available in the press notes list.',
                ),
                new OA\Response(
                    response: 400,
                    description: 'Invalid request data - validation errors (e.g., missing required fields, invalid URL format, invalid image)',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponseSchema::class)),
                ),
                new OA\Response(
                    response: 401,
                    description: 'Unauthorized - Invalid or missing authentication credentials',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponseSchema::class)),
                ),
                new OA\Response(
                    response: 403,
                    description: 'Forbidden - Authenticated but insufficient permissions to create press notes',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponseSchema::class)),
                ),
                new OA\Response(
                    response: 413,
                    description: 'File too large - Image exceeds the 2MB size limit',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponseSchema::class)),
                ),
                new OA\Response(
                    response: 415,
                    description: 'Unsupported media type - Invalid image format (only JPEG, PNG, GIF accepted)',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponseSchema::class)),
                ),
            ],
        );
    }
}
