<?php

declare(strict_types=1);

namespace App\Domain\Exception\Route;

use App\Domain\Exception\Exception;
use App\Domain\Model\Route\Segment;

use function sprintf;

final class SegmentIsFullException extends Exception
{
    public static function fromSegment(Segment $segment): self
    {
        return new self(
            sprintf('Segment with id = %s reached its capacity (%d)', $segment->id(), $segment->capacity()),
        );
    }
}
