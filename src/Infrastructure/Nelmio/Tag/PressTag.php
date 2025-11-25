<?php

namespace App\Infrastructure\Nelmio\Tag;

use Attribute;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class PressTag extends OA\Tag
{
    public function __construct()
    {
        parent::__construct(
            name: 'Press',
            description: 'Operations for managing press notes and releases',
        );
    }
}
