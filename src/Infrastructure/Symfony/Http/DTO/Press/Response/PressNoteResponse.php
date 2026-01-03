<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Press\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(property: 'id', type: 'string', format: 'uuid', example: '9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d'),
        new OA\Property(property: 'title', type: 'string', example: 'Correllengua 2025 Announcement'),
        new OA\Property(property: 'subtitle', type: 'string', example: 'Join us for the biggest event of the year', nullable: false),
        new OA\Property(property: 'body', type: 'string', example: 'Full content of the press release...'),
        new OA\Property(property: 'featured', type: 'boolean', example: true),
        new OA\Property(property: 'image', type: 'string', example: '/uploads/2025/11/25/image.jpg', nullable: false),
        new OA\Property(property: 'link', type: 'string', example: 'www.example.cat/path', nullable: true),
        new OA\Property(property: 'createdAt', type: 'string', format: 'date-time', example: '2025-11-25T12:00:00+00:00'),
    ],
    type: 'object',
)]
final readonly class PressNoteResponse
{
    public function __construct(
        public string $id,
        public string $title,
        public string $subtitle,
        public string $body,
        public bool $featured,
        public string $image,
        public ?string $link,
        public string $createdAt,
    ) {
    }
}
