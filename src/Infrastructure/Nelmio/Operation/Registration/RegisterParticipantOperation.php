<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Operation\Registration;

use App\Infrastructure\Nelmio\Schema\Common\ErrorResponseSchema;
use App\Infrastructure\Nelmio\Schema\Registration\RegisterParticipantRequestSchema;
use Attribute;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_METHOD)]
final class RegisterParticipantOperation extends OA\Post
{
    public function __construct()
    {
        parent::__construct(
            path: '/registration',
            description: 'Register a new or existing participant to one or more route segments (max 5)',
            summary: 'Register a participant to segments',
            requestBody: new OA\RequestBody(
                required: true,
                content: new OA\JsonContent(ref: new Model(type: RegisterParticipantRequestSchema::class)),
            ),
            tags: ['Registration'],
            responses: [
                new OA\Response(
                    response: 201,
                    description: 'Participant registered successfully',
                ),
                new OA\Response(
                    response: 400,
                    description: 'Invalid request data or business rule violation',
                    content: new OA\JsonContent(
                        properties: [
                            new OA\Property(property: 'error', type: 'string', example: 'Validation failed'),
                            new OA\Property(
                                property: 'violations',
                                type: 'array',
                                items: new OA\Items(
                                    properties: [
                                        new OA\Property(property: 'field', type: 'string', example: 'segments'),
                                        new OA\Property(
                                            property: 'message',
                                            type: 'string',
                                            example: 'The segments array cannot contain more than 5 elements.',
                                        ),
                                    ],
                                    type: 'object',
                                ),
                            ),
                        ],
                        type: 'object',
                    ),
                ),
                new OA\Response(
                    response: 409,
                    description: 'Conflict - segment is full, participant already joined, or max segments reached',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponseSchema::class)),
                ),
            ],
        );
    }
}
