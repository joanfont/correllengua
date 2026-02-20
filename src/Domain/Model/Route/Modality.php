<?php

declare(strict_types=1);

namespace App\Domain\Model\Route;

use App\Domain\Model\EnumValues;

enum Modality: string
{
    /** @use EnumValues<self> */
    use EnumValues;

    case WALK = 'WALK';

    case BIKE = 'BIKE';

    case MIXED = 'MIXED';

    case END = 'END';
}
