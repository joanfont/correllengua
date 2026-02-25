<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\Model\Route\Modality;

use function array_map;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use InvalidArgumentException;

use function is_string;
use function max;

use Override;

class ModalityType extends Type
{
    public static function name(): string
    {
        return 'modality';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $enumValues = Modality::values();
        $lengths = array_map(mb_strlen(...), $enumValues);
        $maxEnumLength = [] === $lengths ? 10 : max($lengths);

        return $platform->getStringTypeDeclarationSQL(['length' => $maxEnumLength, 'nullable' => false]);
    }

    #[Override]
    public function convertToPHPValue($value, AbstractPlatform $platform): Modality
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException(sprintf('Modality value must be a string, %s given', get_debug_type($value)));
        }

        return Modality::from($value);
    }

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if ($value instanceof Modality) {
            return $value->value;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException('Modality value must be a string or Modality instance');
        }

        return $value;
    }

    #[Override]
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return false;
    }
}
