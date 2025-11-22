<?php

namespace App\Domain\Provider\Press;

use App\Domain\DTO\Press\PressNote;

interface PressNoteProvider
{
    /**
     * @return array<int, PressNote>
     */
    public function listAll(): array;
}
