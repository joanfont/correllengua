<?php

declare(strict_types=1);

namespace App\Domain\Repository\Route;

use App\Domain\Exception\Route\RouteNotFoundException;
use App\Domain\Model\Route\Route;
use App\Domain\Model\Route\RouteId;

interface RouteRepository
{
    public function add(Route $route): void;

    /** @throws RouteNotFoundException */
    public function findById(RouteId $id): Route;

    public function findByName(string $name): Route;

    public function deleteAll(): void;
}
