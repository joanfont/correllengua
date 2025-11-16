<?php

namespace App\Application\Service\Route;

use App\Application\Service\Calendar;
use App\Application\Service\Route\DTO\Route;

class RouteParser
{
    public function __construct(private readonly Calendar $calendar)
    {
    }

    /**
     * @param array{
     *     code: int,
     *     name: string,
     *     description: string,
     *     start_date: string,
     * } $route
     */
    public function fromArray(array $route): Route
    {
        return new Route(
            code: $route['code'],
            name: $route['name'],
            description: $route['description'],
            startDate: $this->calendar->fromString($route['start_date'], 'd/m/Y'),
        );
    }
}
