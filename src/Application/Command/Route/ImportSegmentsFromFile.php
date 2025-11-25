<?php

namespace App\Application\Command\Route;

use App\Application\Commons\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ImportSegmentsFromFile implements Command
{
    public function __construct(
        #[Assert\NotBlank]
        public string $path,
    ) {
    }
}
