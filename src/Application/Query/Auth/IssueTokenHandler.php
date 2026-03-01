<?php

declare(strict_types=1);

namespace App\Application\Query\Auth;

use App\Application\Commons\Query\QueryHandler;
use App\Domain\DTO\Auth\Token;

use function base64_encode;
use function sprintf;

readonly class IssueTokenHandler implements QueryHandler
{
    public function __invoke(IssueToken $issueToken): Token
    {
        $token = base64_encode(sprintf('%s:%s', $issueToken->email, $issueToken->password));

        return new Token(
            tokenType: 'basic',
            token: $token,
        );
    }
}

