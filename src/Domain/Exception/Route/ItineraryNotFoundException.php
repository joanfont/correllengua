<?php

declare(strict_types=1);

namespace App\Domain\Exception\Route;

use App\Domain\Exception\NotFoundException;
use App\Domain\Model\Route\ItineraryId;

use function sprintf;

final class ItineraryNotFoundException extends NotFoundException
{
    public static function fromId(ItineraryId $id): self
    {
        return new self(sprintf('Itinerary with id = %s not found', $id));
    }

    public static function fromName(string $name): self
    {
        return new self(sprintf('Itinerary with name = %s not found', $name));
    }
}
