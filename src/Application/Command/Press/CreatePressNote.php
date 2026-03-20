<?php

declare(strict_types=1);

namespace App\Application\Command\Press;

use App\Application\Commons\Command\Command;
use App\Domain\DTO\User\User;
use SplFileInfo;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CreatePressNote implements Command
{
    public function __construct(
        public User $user,
        #[Assert\NotBlank]
        public string $title,
        #[Assert\NotBlank]
        public string $subtitle,
        #[Assert\NotBlank]
        public string $body,
        public bool $featured,
        #[Assert\Image(maxSize: '2M')]
        public SplFileInfo $image,
        #[Assert\AtLeastOneOf([
            new Assert\NotBlank(allowNull: true),
            new Assert\Url(),
        ])]
        public ?string $link = null,
    ) {
    }
}
