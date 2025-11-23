<?php

namespace App\Tests\Unit\Application\Service\Route;

use App\Application\Service\Route\RouteParser;
use App\Application\Service\Calendar\Calendar;
use App\Application\Service\Route\DTO\Route as RouteDTO;
use App\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class RouteParserTest extends TestCase
{
    private Calendar&MockObject $calendar;

    protected function setUp(): void
    {
        $this->calendar = $this->createMock(Calendar::class);
    }

    public function testFromArrayParsesFieldsAndUsesCalendar(): void
    {
        $input = [
            'code' => 42,
            'name' => 'My Route',
            'description' => 'A lovely route',
            'start_date' => '23/11/2025',
        ];

        $expectedDate = new \DateTimeImmutable('2025-11-23');

        $this->calendar
            ->expects(static::once())
            ->method('fromString')
            ->with($input['start_date'], 'd/m/Y')
            ->willReturn($expectedDate);

        $parser = new RouteParser($this->calendar);

        $route = $parser->fromArray($input);

        static::assertInstanceOf(RouteDTO::class, $route);
        static::assertSame(42, $route->code);
        static::assertSame('My Route', $route->name);
        static::assertSame('A lovely route', $route->description);
        static::assertEquals($expectedDate, $route->startDate);
    }
}

