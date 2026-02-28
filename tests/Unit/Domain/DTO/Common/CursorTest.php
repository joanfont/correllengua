<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\DTO\Common;

use App\Domain\DTO\Common\Cursor;

use function base64_encode;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CursorTest extends TestCase
{
    public function testCreatesFromEncodedValue(): void
    {
        $value = 'test-value';
        $encoded = base64_encode($value);

        $cursor = Cursor::fromEncoded($encoded);

        self::assertSame($value, $cursor->value());
        self::assertSame($value, (string) $cursor);
    }

    public function testCreatesFromValue(): void
    {
        $value = 'test-value';

        $cursor = Cursor::fromValue($value);

        self::assertSame($value, $cursor->value());
    }

    public function testEncodesValue(): void
    {
        $value = 'test-value';
        $cursor = Cursor::fromValue($value);

        $encoded = $cursor->encode();

        self::assertSame(base64_encode($value), $encoded);
    }

    public function testThrowsExceptionForEmptyValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cursor value cannot be empty');

        Cursor::fromValue('');
    }

    public function testThrowsExceptionForInvalidEncodedValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid cursor: unable to decode');

        Cursor::fromEncoded('!!!invalid-base64!!!');
    }

    public function testThrowsExceptionForEmptyEncodedValue(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Cursor::fromEncoded(base64_encode(''));
    }
}
