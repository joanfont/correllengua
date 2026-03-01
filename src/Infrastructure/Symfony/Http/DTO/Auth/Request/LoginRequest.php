<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\DTO\Auth\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    required: ['email', 'password'],
    properties: [
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@correllengua.cat'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'secret'),
    ],
    type: 'object',
)]
final readonly class LoginRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,
        #[Assert\NotBlank]
        public string $password,
    ) {
    }
}
