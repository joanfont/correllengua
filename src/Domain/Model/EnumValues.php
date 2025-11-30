<?php

namespace App\Domain\Model;

use function array_map;

use BackedEnum;

/**
 * @template T of BackedEnum
 */
trait EnumValues
{
    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(fn (BackedEnum $e): string => (string) $e->value, static::cases());
    }
}
