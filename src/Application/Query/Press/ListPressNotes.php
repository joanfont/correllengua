<?php

namespace App\Application\Query\Press;

use App\Application\Commons\Query\Query;
use App\Domain\DTO\Press\PressNote;

/**
 * @implements Query<array<PressNote>>
 */
readonly class ListPressNotes implements Query
{
}
