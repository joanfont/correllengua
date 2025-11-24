<?php

namespace App\Tests\Unit\Application\Service\Route;

use App\Application\Service\Route\ItineraryBuilder;
use App\Application\Service\Route\DTO\Itinerary as ItineraryDTO;
use App\Tests\TestCase;

class ItineraryBuilderTest extends TestCase
{
    public function testFromArrayReturnsItineraryDTO(): void
    {
        $input = [
            'route_name' => 'Route Test',
            'name' => 'Itinerary Test',
        ];

        $builder = new ItineraryBuilder();

        $itinerary = $builder->fromArray($input);

        static::assertInstanceOf(ItineraryDTO::class, $itinerary);
        static::assertSame($input['route_name'], $itinerary->routeName);
        static::assertSame($input['name'], $itinerary->name);
    }
}

