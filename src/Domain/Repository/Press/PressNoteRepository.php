<?php

namespace App\Domain\Repository\Press;

use App\Domain\Model\Press\PressNote;

interface PressNoteRepository
{
    public function add(PressNote $pressNote): void;
}
