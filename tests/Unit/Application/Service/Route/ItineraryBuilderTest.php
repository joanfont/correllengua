<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Service\Route;

use App\Application\Service\Route\DTO\Itinerary as ItineraryDTO;
use App\Application\Service\Route\ItineraryBuilder;
use App\Tests\TestCase;

class ItineraryBuilderTest extends TestCase
{
    public function testFromArrayReturnsItineraryDTO(): void
    {
        $input = [
            'route_name' => 'Route Test',
            'name' => 'Itinerary Test',
            'position' => '1',
        ];

        $builder = new ItineraryBuilder();

        $itinerary = $builder->fromArray($input);

        self::assertInstanceOf(ItineraryDTO::class, $itinerary);
        self::assertSame($input['route_name'], $itinerary->routeName);
        self::assertSame($input['name'], $itinerary->name);
        self::assertSame(1, $itinerary->position);
    }
}
