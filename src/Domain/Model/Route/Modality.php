<?php

namespace App\Domain\Model\Route;

use App\Domain\Model\EnumValues;

enum Modality: string
{
    use EnumValues;

    case WALK = 'WALK';
    case BIKE = 'BIKE';
    case MIXED = 'MIXED';
}
