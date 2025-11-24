<?php

namespace App\Domain\Repository\Route;

use App\Domain\Model\Route\Route;

interface RouteRepository
{
    public function add(Route $route): void;

    public function findByName(string $name): Route;
}
