<?php

namespace App\Infrastructure\Symfony\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
class MaxSegmentsCount extends Constraint
{
    public string $message = 'The segments array cannot contain more than {{ limit }} elements.';

    public function __construct(
        ?array $options = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options, $groups, $payload);
    }
}
