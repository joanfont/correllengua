<?php

namespace App\Domain\Exception\Route;

use App\Domain\Exception\Exception;
use App\Domain\Model\Route\Segment;

final class SegmentIsFullException extends Exception
{
    public static function fromSegment(Segment $segment): self
    {
        return new self(
            sprintf('Segment with id = %s reached its capacity (%d)', $segment->id(), $segment->capacity())
        );
    }
}
