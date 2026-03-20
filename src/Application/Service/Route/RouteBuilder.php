<?php

declare(strict_types=1);

namespace App\Application\Service\Route;

use App\Application\Service\Calendar\Calendar;
use App\Application\Service\Route\DTO\Route;

readonly class RouteBuilder
{
    public function __construct(private Calendar $calendar)
    {
    }

    /**
     * @param array{
     *     name: string,
     *     description: string,
     *     position: string,
     *     start_date: string,
     * } $route
     */
    public function fromArray(array $route): Route
    {
        return new Route(
            name: $route['name'],
            description: $route['description'],
            position: (int) $route['position'],
            startDate: $this->calendar->fromString($route['start_date'], 'd/m/y'),
        );
    }
}
