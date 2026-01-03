<?php

declare(strict_types=1);

namespace App\Application\Command\User;

use App\Application\Commons\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateUser implements Command
{
    public function __construct(
        #[Assert\Email]
        public string $email,
        #[Assert\NotBlank]
        public string $password,
    ) {
    }
}
