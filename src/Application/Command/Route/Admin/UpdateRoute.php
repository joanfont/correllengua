<?php

declare(strict_types=1);

namespace App\Application\Command\Route\Admin;

use App\Application\Commons\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

readonly class UpdateRoute implements Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id,
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
