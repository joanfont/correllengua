<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Schema\Press;

use OpenApi\Attributes as OA;

#[OA\Schema(
    description: 'Request body for creating a new press note with optional image upload',
    required: ['title', 'subtitle', 'body', 'featured'],
    properties: [
        new OA\Property(
            property: 'title',
            description: 'Title of the press note (1-255 characters)',
            type: 'string',
            maxLength: 255,
            minLength: 1,
            example: 'Correllengua 2025 Official Announcement',
        ),
        new OA\Property(
            property: 'subtitle',
            description: 'Subtitle or short description of the press note (1-255 characters)',
            type: 'string',
            maxLength: 255,
            minLength: 1,
            example: 'Join us for the biggest event of the year',
        ),
        new OA\Property(
            property: 'body',
            description: 'Full content/body of the press note.',
            type: 'string',
            minLength: 1,
            example: 'We are pleased to announce the Correllengua 2025 event...',
        ),
        new OA\Property(
            property: 'featured',
            description: 'Whether this press note should be featured on the homepage.',
            type: 'boolean',
            example: true,
        ),
        new OA\Property(
            property: 'link',
            description: 'Optional external URL related to this press note.',
            type: 'string',
            format: 'uri',
            example: 'https://example.com/event-details',
            nullable: true,
        ),
    ],
)]
final class CreatePressNoteRequestSchema
{
}
