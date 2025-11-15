<?php

namespace App\Domain\Exception\Route;

use App\Domain\Exception\NotFoundException;
use App\Domain\Model\Route\SegmentId;

final class SegmentNotFoundException extends NotFoundException
{
    public static function fromId(SegmentId $id): self
    {
        return new self(sprintf('Segment with id = %s not found', $id));
    }
}
