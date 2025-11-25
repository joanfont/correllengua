<?php

namespace App\Infrastructure\Nelmio\Tag;

use Attribute;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class RegistrationTag extends OA\Tag
{
    public function __construct()
    {
        parent::__construct(
            name: 'Registration',
            description: 'Operations for participant registration and deregistration',
        );
    }
}
