<?php

declare(strict_types=1);

namespace App\Domain\Model;

use function array_map;

use BackedEnum;

/**
 * @template T of BackedEnum
 */
trait EnumValues
{
    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_map(fn (BackedEnum $e): string => (string) $e->value, static::cases());
    }
}
