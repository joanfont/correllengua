<?php

declare(strict_types=1);

namespace App\Application\Service\Calendar;

use DateTimeImmutable;
use DateTimeInterface;

class Calendar
{
    private const string DEFAULT_FORMAT = 'Y-m-d H:i:s';

    public function now(): DateTimeInterface
    {
        return new DateTimeImmutable('now');
    }

    public function fromString(string $dateTime, ?string $format = null): DateTimeInterface
    {
        $format ??= self::DEFAULT_FORMAT;

        $dt = DateTimeImmutable::createFromFormat($format, $dateTime);

        return $dt instanceof DateTimeImmutable ? $dt : new DateTimeImmutable('now');
    }
}
