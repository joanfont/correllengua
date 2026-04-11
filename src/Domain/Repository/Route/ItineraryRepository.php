<?php

declare(strict_types=1);

namespace App\Domain\Repository\Route;

use App\Domain\Exception\Route\ItineraryNotFoundException;
use App\Domain\Model\Route\Itinerary;
use App\Domain\Model\Route\ItineraryId;

interface ItineraryRepository
{
    public function add(Itinerary $itinerary): void;

    /** @throws ItineraryNotFoundException */
    public function findById(ItineraryId $id): Itinerary;

    public function findByName(string $name): Itinerary;

    public function deleteAll(): void;
}
