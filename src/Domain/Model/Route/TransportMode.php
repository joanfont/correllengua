<?php

namespace App\Domain\Model\Route;

use App\Domain\Model\EnumValues;

enum TransportMode: string
{
    use EnumValues;

    case WALK = 'WALK';
    case CYCLE = 'CYCLE';
    case MIXED = 'MIXED';
}
