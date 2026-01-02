<?php

namespace App\Domain\Model;

use function array_map;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Stringable;

abstract class Uuid implements Stringable
{
    public function __construct(private readonly string $id)
    {
        if (!RamseyUuid::isValid($id)) {
            throw new InvalidArgumentException('Invalid UUID: '.$id);
        }
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
        /** @var static $instance */
        $instance = new static((string) RamseyUuid::uuid4());

        return $instance;
    }

    public static function from(string $id): static
    {
        /** @var static $instance */
        $instance = new static($id);

        return $instance;
    }

    /**
     * @param array<string> $ids
     *
     * @return array<static>
     */
    final public static function fromMany(array $ids): array
    {
        return array_map(
            static::from(...),
            $ids,
        );
    }
}
