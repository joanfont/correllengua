<?php

namespace App\Domain\Model;

use function array_map;

use Ramsey\Uuid\Uuid as RamseyUuid;
use Stringable;

abstract class Uuid implements Stringable
{
    public function __construct(private readonly string $id)
    {
        RamseyUuid::isValid($id);
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function equals(self $uuid): bool
    {
        return (string) $uuid === $this->id;
    }

    public static function generate(): static
    {
        return static::v4();
    }

    protected static function v4(): static
    {
        return new static(RamseyUuid::uuid4());
    }

    public static function from(string $id): static
    {
        return new static($id);
    }

    final public static function fromMany(array $ids): array
    {
        return array_map(static::from(...), $ids);
    }
}
