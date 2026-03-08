<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Operation\Registration;

use App\Infrastructure\Nelmio\Schema\Common\ErrorResponseSchema;
use Attribute;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_METHOD)]
final class DeregisterParticipantOperation extends OA\Get
{
    public function __construct()
    {
        parent::__construct(
            path: '/registration/deregister',
            description: 'Remove a participant registration using the unique hash provided in the registration confirmation',
            summary: 'Deregister a participant from a segment',
            tags: ['Registration'],
            parameters: [
                new OA\Parameter(
                    name: 'hash',
                    description: 'The unique registration hash for deregistration',
                    in: 'query',
                    required: true,
                    schema: new OA\Schema(type: 'string', example: 'abc123def456'),
                ),
            ],
            responses: [
                new OA\Response(
                    response: 204,
                    description: 'Participant deregistered successfully',
                ),
                new OA\Response(
                    response: 404,
                    description: 'Registration not found',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponseSchema::class)),
                ),
            ],
        );
    }
}
