<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\Controller\Auth;

use App\Application\Command\Auth\ValidateCredentials;
use App\Application\Commons\Command\CommandBus;
use App\Application\Commons\Query\QueryBus;
use App\Application\Query\Auth\IssueToken;
use App\Domain\DTO\Auth\Token;
use App\Infrastructure\Nelmio\Operation\Auth\LoginOperation;
use App\Infrastructure\Nelmio\Tag\AuthTag;
use App\Infrastructure\Symfony\Http\DTO\Auth\Request\LoginRequest;
use App\Infrastructure\Symfony\Http\DTO\Auth\Response\TokenResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/auth')]
#[AuthTag]
final class AuthController extends AbstractController
{
    #[Route('/login', name: 'auth_login', methods: ['POST'])]
    #[LoginOperation]
    public function login(
        CommandBus $commandBus,
        QueryBus $queryBus,
        #[MapRequestPayload]
        LoginRequest $loginRequest,
    ): JsonResponse {
        $commandBus->dispatch(new ValidateCredentials(
            email: $loginRequest->email,
            password: $loginRequest->password,
        ));

        /** @var Token $token */
        $token = $queryBus->query(new IssueToken(
            email: $loginRequest->email,
            password: $loginRequest->password,
        ));

        return $this->json(new TokenResponse(
            token_type: $token->tokenType,
            token: $token->token,
        ));
    }
}
