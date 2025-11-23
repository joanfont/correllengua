<?php

namespace App\Domain\Model;

use BackedEnum;

/**
 * @template T of BackedEnum
 */
trait EnumValues
{
    /**
     * @return list<T::value>
     */
    public static function values(): array
    {
        return array_map(fn (BackedEnum $e) => $e->value, static::cases());
    }
}
