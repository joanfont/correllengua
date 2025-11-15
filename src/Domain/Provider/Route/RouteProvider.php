<?php

namespace App\Domain\Provider\Route;

use App\Domain\DTO\Route\Route;

interface RouteProvider
{
    /**
     * @return array<int, Route>
     */
    public function findAll(): array;
}
