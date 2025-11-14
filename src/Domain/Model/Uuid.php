<?php

namespace App\Domain\Model;

use Ramsey\Uuid\Uuid as RamseyUuid;

abstract class Uuid
{
    public function __construct(private readonly string $id)
    {
        RamseyUuid::isValid($id);
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function equals(Uuid $uuid): bool
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
        return array_map(fn (string $id) => static::from($id), $ids);
    }
}
