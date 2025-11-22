<?php

namespace App\Application\Command\Press;

use App\Application\Commons\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CreatePressNote implements Command
{
    public function __construct(
        #[Assert\NotBlank]
        public string $title,
        #[Assert\NotBlank]
        public string $subtitle,
        #[Assert\NotBlank]
        public string $body,
        public bool $featured,
        #[Assert\Image(maxSize: '2M')]
        public \SplFileInfo $image,
    ) {
    }
}
