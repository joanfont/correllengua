<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Validator\Constraints;

use function count;

use Countable;

use function is_array;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class MaxSegmentsCountValidator extends ConstraintValidator
{
    public function __construct(
        private readonly int $maxSegmentsPerParticipant,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof MaxSegmentsCount) {
            throw new UnexpectedTypeException($constraint, MaxSegmentsCount::class);
        }

        if (null === $value) {
            return;
        }

        if (!is_array($value) && !$value instanceof Countable) {
            throw new UnexpectedValueException($value, 'array|\Countable');
        }

        $count = count($value);

        if ($count > $this->maxSegmentsPerParticipant) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ limit }}', (string) $this->maxSegmentsPerParticipant)
                ->setParameter('{{ count }}', (string) $count)
                ->setPlural($this->maxSegmentsPerParticipant)
                ->setCode('MAX_SEGMENTS_COUNT_ERROR')
                ->addViolation();
        }
    }
}
