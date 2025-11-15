<?php

namespace App\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Types\Type as DoctrineType;

abstract class Type extends DoctrineType
{
    abstract public static function name(): string;

    public function getName(): string
    {
        return static::type();
    }
}