<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Press;

use OpenApi\Attributes as OA;

#[OA\Schema(
    required: ['title', 'body', 'featured'],
    properties: [
        new OA\Property(
            property: 'title',
            type: 'string',
            minLength: 1,
            maxLength: 255,
            example: 'Correllengua 2025 Official Announcement',
        ),
        new OA\Property(
            property: 'subtitle',
            type: 'string',
            nullable: true,
            maxLength: 255,
            example: 'Join us for the biggest event of the year',
        ),
        new OA\Property(
            property: 'body',
            type: 'string',
            minLength: 1,
            example: 'We are pleased to announce the Correllengua 2025 event...',
        ),
        new OA\Property(
            property: 'featured',
            type: 'boolean',
            description: 'Whether this press note should be featured on the homepage',
            example: true,
        ),
        new OA\Property(
            property: 'image',
            type: 'string',
            format: 'binary',
            description: 'Press note image file (JPEG, PNG, GIF)',
            nullable: true,
        ),
    ],
)]
readonly class CreatePressNoteRequest
{
    public function __construct(
        public string $title,
        public string $subtitle,
        public string $body,
        public bool $featured,
        public \SplFileInfo $image,
    ) {}
}
