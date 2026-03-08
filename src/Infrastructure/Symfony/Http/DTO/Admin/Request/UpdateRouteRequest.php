<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Admin\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateRouteRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $name,
        #[Assert\NotBlank]
        public string $description,
        #[Assert\Positive]
        public int $position,
        #[Assert\NotBlank]
        public string $startsAt,
    ) {
    }
}
