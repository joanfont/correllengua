<?php

declare(strict_types=1);

namespace App\Domain\DTO\Common;

use function base64_decode;
use function base64_encode;

use InvalidArgumentException;

final readonly class Cursor
{
    private function __construct(
        private string $value,
    ) {
        if ('' === $value) {
            throw new InvalidArgumentException('Cursor value cannot be empty');
        }
    }

    public static function fromEncoded(string $encoded): self
    {
        $decoded = base64_decode($encoded, true);

        if (false === $decoded || '' === $decoded) {
            throw new InvalidArgumentException('Invalid cursor: unable to decode');
        }

        return new self($decoded);
    }

    public static function fromValue(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function encode(): string
    {
        return base64_encode($this->value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
