<?php

namespace App\Application\Query\Press;

use App\Application\Commons\Query\QueryHandler;
use App\Domain\DTO\Press\PressNote;
use App\Domain\Provider\Press\PressNoteProvider;

readonly class ListPressNotesHandler implements QueryHandler
{
    public function __construct(
        private PressNoteProvider $pressNoteProvider,
    ) {
    }

    /**
     * @return array<PressNote>
     */
    public function __invoke(ListPressNotes $listPressNotes): array
    {
        return $this->pressNoteProvider->listAll();
    }
}
