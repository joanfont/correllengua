<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Symfony\Validator\Constraints;

use App\Infrastructure\Symfony\Validator\Constraints\MaxSegmentsCount;
use App\Infrastructure\Symfony\Validator\Constraints\MaxSegmentsCountValidator;
use ArrayObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

final class MaxSegmentsCountValidatorTest extends TestCase
{
    private \PHPUnit\Framework\MockObject\MockObject $context;

    private MaxSegmentsCountValidator $validator;

    protected function setUp(): void
    {
        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->validator = new MaxSegmentsCountValidator(maxSegmentsPerParticipant: 5);
        $this->validator->initialize($this->context);
    }

    public function testValidateWithNullValueDoesNotAddViolation(): void
    {
        $constraint = new MaxSegmentsCount();

        $this->context
            ->expects($this->never())
            ->method('buildViolation');

        $this->validator->validate(null, $constraint);
    }

    public function testValidateWithArrayBelowMaxDoesNotAddViolation(): void
    {
        $constraint = new MaxSegmentsCount();
        $value = ['segment1', 'segment2', 'segment3'];

        $this->context
            ->expects($this->never())
            ->method('buildViolation');

        $this->validator->validate($value, $constraint);
    }

    public function testValidateWithArrayAtMaxDoesNotAddViolation(): void
    {
        $constraint = new MaxSegmentsCount();
        $value = ['segment1', 'segment2', 'segment3', 'segment4', 'segment5'];

        $this->context
            ->expects($this->never())
            ->method('buildViolation');

        $this->validator->validate($value, $constraint);
    }

    public function testValidateWithEmptyArrayDoesNotAddViolation(): void
    {
        $constraint = new MaxSegmentsCount();
        $value = [];

        $this->context
            ->expects($this->never())
            ->method('buildViolation');

        $this->validator->validate($value, $constraint);
    }

    public function testValidateWithArrayAboveMaxAddsViolation(): void
    {
        $constraint = new MaxSegmentsCount();
        $value = ['segment1', 'segment2', 'segment3', 'segment4', 'segment5', 'segment6'];

        $violationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);

        $this->context
            ->expects($this->once())
            ->method('buildViolation')
            ->with($constraint->message)
            ->willReturn($violationBuilder);

        $violationBuilder
            ->expects($this->exactly(2))
            ->method('setParameter')
            ->willReturnCallback(function (string $key, string $value) use ($violationBuilder): \PHPUnit\Framework\MockObject\MockObject {
                self::assertContains($key, ['{{ limit }}', '{{ count }}']);
                self::assertContains($value, ['5', '6']);

                return $violationBuilder;
            });

        $violationBuilder
            ->expects($this->once())
            ->method('setPlural')
            ->with(5)
            ->willReturnSelf();

        $violationBuilder
            ->expects($this->once())
            ->method('setCode')
            ->with('MAX_SEGMENTS_COUNT_ERROR')
            ->willReturnSelf();

        $violationBuilder
            ->expects($this->once())
            ->method('addViolation');

        $this->validator->validate($value, $constraint);
    }

    public function testValidateWithCountableObjectAboveMaxAddsViolation(): void
    {
        $constraint = new MaxSegmentsCount();
        $value = new ArrayObject(['s1', 's2', 's3', 's4', 's5', 's6']);

        $violationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);

        $this->context
            ->expects($this->once())
            ->method('buildViolation')
            ->with($constraint->message)
            ->willReturn($violationBuilder);

        $violationBuilder
            ->expects($this->exactly(2))
            ->method('setParameter')
            ->willReturnCallback(function (string $key, string $value) use ($violationBuilder): \PHPUnit\Framework\MockObject\MockObject {
                self::assertContains($key, ['{{ limit }}', '{{ count }}']);
                self::assertContains($value, ['5', '6']);

                return $violationBuilder;
            });

        $violationBuilder
            ->expects($this->once())
            ->method('setPlural')
            ->with(5)
            ->willReturnSelf();

        $violationBuilder
            ->expects($this->once())
            ->method('setCode')
            ->with('MAX_SEGMENTS_COUNT_ERROR')
            ->willReturnSelf();

        $violationBuilder
            ->expects($this->once())
            ->method('addViolation');

        $this->validator->validate($value, $constraint);
    }

    public function testValidateThrowsExceptionForWrongConstraintType(): void
    {
        $wrongConstraint = $this->createMock(Constraint::class);

        $this->expectException(UnexpectedTypeException::class);

        $this->validator->validate([], $wrongConstraint);
    }

    public function testValidateThrowsExceptionForInvalidValueType(): void
    {
        $constraint = new MaxSegmentsCount();

        $this->expectException(UnexpectedValueException::class);

        $this->validator->validate('invalid', $constraint);
    }

    public function testValidateThrowsExceptionForIntegerValue(): void
    {
        $constraint = new MaxSegmentsCount();

        $this->expectException(UnexpectedValueException::class);

        $this->validator->validate(123, $constraint);
    }

    public function testValidateThrowsExceptionForObjectValue(): void
    {
        $constraint = new MaxSegmentsCount();

        $this->expectException(UnexpectedValueException::class);

        $this->validator->validate(new stdClass(), $constraint);
    }
}
