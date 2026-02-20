<?php

declare(strict_types=1);

namespace App\Domain\Repository\Route;

use App\Domain\Model\Route\Itinerary;

interface ItineraryRepository
{
    public function add(Itinerary $itinerary): void;

    public function findByName(string $name): Itinerary;

    public function deleteAll(): void;
}
