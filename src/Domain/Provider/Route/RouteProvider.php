<?php

declare(strict_types=1);

namespace App\Domain\Provider\Route;

use App\Domain\DTO\Route\Route;

interface RouteProvider
{
    /**
     * @return array<Route>
     */
    public function findAll(): array;
}
