<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Press;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

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
            description: 'Full content/body of the press note. Supports plain text and basic formatting.',
            type: 'string',
            minLength: 1,
            example: 'We are pleased to announce the Correllengua 2025 event, which will take place across multiple locations...',
        ),
        new OA\Property(
            property: 'featured',
            description: 'Whether this press note should be featured/highlighted on the homepage. Featured notes appear prominently to visitors.',
            type: 'boolean',
            example: true,
        ),
        new OA\Property(
            property: 'link',
            description: 'Optional external URL related to this press note (e.g., event page, external article, registration form). Must be a valid URL.',
            type: 'string',
            format: 'uri',
            example: 'https://example.com/event-details',
            nullable: true,
        ),
    ],
)]
readonly class CreatePressNoteRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public string $title,
        #[Assert\NotBlank]
        public string $subtitle,
        #[Assert\NotBlank]
        public string $body,
        public bool $featured,
        #[Assert\AtLeastOneOf([
            new Assert\NotBlank(allowNull: true),
            new Assert\Url(),
        ])]
        public ?string $link = null,
    ) {
    }
}
