<?php

namespace App\Application\Query\Press;

use App\Application\Commons\Query\Query;
use App\Domain\DTO\Press\PressNote;

/**
 * @implements Query<array<int, PressNote>>
 */
readonly class ListPressNotes implements Query {}
