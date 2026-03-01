<?php

declare(strict_types=1);

namespace App\Infrastructure\Nelmio\Operation\Auth;

use App\Infrastructure\Symfony\Http\DTO\Auth\Request\LoginRequest;
use App\Infrastructure\Symfony\Http\DTO\Auth\Response\TokenResponse;
use App\Infrastructure\Symfony\Http\DTO\Common\ErrorResponse;
use Attribute;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_METHOD)]
final class LoginOperation extends OA\Post
{
    public function __construct()
    {
        parent::__construct(
            path: '/auth/login',
            description: 'Validates user credentials and returns a Basic Auth token to use in subsequent requests.',
            summary: 'Login and obtain a Basic Auth token',
            requestBody: new OA\RequestBody(
                required: true,
                content: new OA\JsonContent(ref: new Model(type: LoginRequest::class)),
            ),
            tags: ['Auth'],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Authentication successful',
                    content: new OA\JsonContent(ref: new Model(type: TokenResponse::class)),
                ),
                new OA\Response(
                    response: 401,
                    description: 'Invalid credentials',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponse::class)),
                ),
                new OA\Response(
                    response: 422,
                    description: 'Validation error',
                    content: new OA\JsonContent(ref: new Model(type: ErrorResponse::class)),
                ),
            ],
        );
    }
}

