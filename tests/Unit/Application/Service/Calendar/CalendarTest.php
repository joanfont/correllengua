<?php

namespace App\Tests\Unit\Application\Service\Calendar;

use App\Application\Service\Calendar\Calendar;
use App\Tests\TestCase;
use DateTimeInterface;

use function time;

class CalendarTest extends TestCase
{
    public function testNowReturnsDateTimeInterfaceCloseToCurrentTime(): void
    {
        $calendar = new Calendar();

        $now = $calendar->now();

        static::assertInstanceOf(DateTimeInterface::class, $now);

        $nowTimestamp = $now->getTimestamp();
        $current = time();

        // now() should return a time not in the future and not older than a small window
        static::assertLessThanOrEqual($current, $nowTimestamp);
        static::assertGreaterThanOrEqual($current - 5, $nowTimestamp);
    }

    public function testFromStringParsesDefaultAndCustomFormats(): void
    {
        $calendar = new Calendar();

        $dateStr = '2025-11-23 12:34:56';
        $dt = $calendar->fromString($dateStr);

        static::assertInstanceOf(DateTimeInterface::class, $dt);
        static::assertSame($dateStr, $dt->format('Y-m-d H:i:s'));

        $dateDay = '23/11/2025';
        $dt2 = $calendar->fromString($dateDay, 'd/m/Y');

        static::assertInstanceOf(DateTimeInterface::class, $dt2);
        static::assertSame($dateDay, $dt2->format('d/m/Y'));
    }
}
