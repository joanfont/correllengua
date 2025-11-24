<?php

namespace App\Application\Query\Route;

use App\Application\Commons\Query\Query;
use App\Domain\DTO\Route\Route;

/**
 * @implements Query<array<int, Route>>
 */
readonly class ListRoutes implements Query {}
