<?php

namespace App\Application\Service\Calendar;

class Calendar
{
    private const string DEFAULT_FORMAT = 'Y-m-d H:i:s';

    public function now(): \DateTimeInterface
    {
        return new \DateTimeImmutable('now');
    }

    public function fromString(string $dateTime, ?string $format = null): \DateTimeInterface
    {
        $format ??= self::DEFAULT_FORMAT;

        return \DateTimeImmutable::createFromFormat($format, $dateTime);
    }
}
