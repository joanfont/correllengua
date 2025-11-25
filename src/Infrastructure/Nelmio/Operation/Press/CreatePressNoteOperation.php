<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Operation\Press;

use App\Infrastructure\Symfony\Http\DTO\Common\ErrorResponse;
use App\Infrastructure\Symfony\Http\DTO\Press\CreatePressNoteRequest;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_METHOD)]
final class CreatePressNoteOperation extends OA\Post
{
    public function __construct()
    {
        parent::__construct(
            path: '/press',
            summary: 'Create a new press note',
            description: 'Create a new press note with optional image upload',
            requestBody: new OA\RequestBody(
                required: true,
                content: new OA\MediaType(
                    mediaType: 'multipart/form-data',
                    schema: new OA\Schema(ref: new Model(type: CreatePressNoteRequest::class)),
                ),
            ),
            tags: ['Press'],
            responses: [
                new OA\Response(
                    response: 201,
                    description: 'Press note created successfully',
                ),
                new OA\Response(
                    response: 400,
                    description: 'Invalid request data',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponse::class)),
                ),
                new OA\Response(
                    response: 413,
                    description: 'File too large',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponse::class)),
                ),
                new OA\Response(
                    response: 415,
                    description: 'Unsupported media type',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponse::class)),
                ),
            ],
        );
    }
}
