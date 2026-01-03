<?php

declare(strict_types=1);

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

        self::assertInstanceOf(DateTimeInterface::class, $now);

        $nowTimestamp = $now->getTimestamp();
        $current = time();

        // now() should return a time not in the future and not older than a small window
        self::assertLessThanOrEqual($current, $nowTimestamp);
        self::assertGreaterThanOrEqual($current - 5, $nowTimestamp);
    }

    public function testFromStringParsesDefaultAndCustomFormats(): void
    {
        $calendar = new Calendar();

        $dateStr = '2025-11-23 12:34:56';
        $dt = $calendar->fromString($dateStr);

        self::assertInstanceOf(DateTimeInterface::class, $dt);
        self::assertSame($dateStr, $dt->format('Y-m-d H:i:s'));

        $dateDay = '23/11/2025';
        $dt2 = $calendar->fromString($dateDay, 'd/m/Y');

        self::assertInstanceOf(DateTimeInterface::class, $dt2);
        self::assertSame($dateDay, $dt2->format('d/m/Y'));
    }
}
