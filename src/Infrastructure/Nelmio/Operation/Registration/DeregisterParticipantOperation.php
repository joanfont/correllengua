<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Operation\Registration;

use App\Infrastructure\Symfony\Http\DTO\Common\ErrorResponse;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[\Attribute(\Attribute::TARGET_METHOD)]
final class DeregisterParticipantOperation extends OA\Get
{
    public function __construct()
    {
        parent::__construct(
            path: '/registration/deregister',
            summary: 'Deregister a participant from a segment',
            description: 'Remove a participant registration using the unique hash provided in the registration confirmation',
            tags: ['Registration'],
            parameters: [
                new OA\Parameter(
                    name: 'hash',
                    in: 'query',
                    description: 'The unique registration hash for deregistration',
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
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponse::class)),
                ),
            ],
        );
    }
}
