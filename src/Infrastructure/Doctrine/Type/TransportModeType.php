<?php

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\Model\Route\TransportMode;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TransportModeType extends Type
{
    public static function name(): string
    {
        return 'transport-mode';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        $enumValues = TransportMode::values();
        $maxEnumLength = max(array_map('mb_strlen', $enumValues));

        return $platform->getStringTypeDeclarationSQL(['length' => $maxEnumLength, 'nullable' => false]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return TransportMode::from($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return false;
    }
}