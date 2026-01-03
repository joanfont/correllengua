<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Operation\Press;

use App\Infrastructure\Symfony\Http\DTO\Common\ErrorResponse;
use App\Infrastructure\Symfony\Http\DTO\Press\Response\PressNoteResponse;
use Attribute;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_METHOD)]
final class ListPressNotesOperation extends OA\Get
{
    public function __construct()
    {
        parent::__construct(
            path: '/press',
            description: 'Returns a list of all published press notes and releases',
            summary: 'List all press notes',
            tags: ['Press'],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'List of press notes retrieved successfully',
                    content: new OA\JsonContent(
                        type: 'array',
                        items: new OA\Items(ref: new Model(type: PressNoteResponse::class)),
                    ),
                ),
                new OA\Response(
                    response: 500,
                    description: 'Internal server error',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponse::class)),
                ),
            ],
        );
    }
}
