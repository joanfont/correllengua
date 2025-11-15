<?php

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\Model\Route\Modality;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class ModalityType extends Type
{
    public static function name(): string
    {
        return 'modality';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $enumValues = Modality::values();
        $maxEnumLength = max(array_map('mb_strlen', $enumValues));

        return $platform->getStringTypeDeclarationSQL(['length' => $maxEnumLength, 'nullable' => false]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Modality
    {
        return Modality::from($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return false;
    }
}
