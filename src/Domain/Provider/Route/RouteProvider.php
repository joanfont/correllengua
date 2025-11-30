<?php

namespace App\Domain\Provider\Route;

use App\Domain\DTO\Route\Route;

interface RouteProvider
{
    /**
     * @return array<Route>
     */
    public function findAll(): array;
}
