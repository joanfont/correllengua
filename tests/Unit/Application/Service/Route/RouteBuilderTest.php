<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Service\Route;

use App\Application\Service\Calendar\Calendar;
use App\Application\Service\Route\DTO\Route as RouteDTO;
use App\Application\Service\Route\RouteBuilder;
use App\Tests\TestCase;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;

class RouteBuilderTest extends TestCase
{
    private Calendar&MockObject $calendar;

    protected function setUp(): void
    {
        $this->calendar = $this->createMock(Calendar::class);
    }

    public function testFromArrayParsesFieldsAndUsesCalendar(): void
    {
        $input = [
            'name' => 'My Route',
            'description' => 'A lovely route',
            'position' => '1',
            'start_date' => '23/11/2025',
        ];

        $expectedDate = new DateTimeImmutable('2025-11-23');

        $this->calendar
            ->expects(static::once())
            ->method('fromString')
            ->with($input['start_date'], 'd/m/y')
            ->willReturn($expectedDate);

        $builder = new RouteBuilder($this->calendar);

        $route = $builder->fromArray($input);

        self::assertInstanceOf(RouteDTO::class, $route);
        self::assertSame('My Route', $route->name);
        self::assertSame('A lovely route', $route->description);
        self::assertSame(1, $route->position);
        self::assertEquals($expectedDate, $route->startDate);
    }
}
