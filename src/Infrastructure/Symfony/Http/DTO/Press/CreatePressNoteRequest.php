<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Press;

use Symfony\Component\Validator\Constraints as Assert;

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
            new Assert\Url(requireTld: false),
        ])]
        public ?string $link = null,
    ) {
    }
}
