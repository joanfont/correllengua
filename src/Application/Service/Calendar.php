<?php

namespace App\Application\Service;

class Calendar
{
    private const string DEFAULT_FORMAT = 'Y-m-d H:i:s';

    public function fromString(string $dateTime, ?string $format = null): \DateTimeInterface
    {
        $format ??= self::DEFAULT_FORMAT;

        return \DateTimeImmutable::createFromFormat($format, $dateTime);
    }
}
