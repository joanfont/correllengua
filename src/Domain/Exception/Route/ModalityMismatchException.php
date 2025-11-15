<?php

namespace App\Domain\Exception\Route;

use App\Domain\Exception\Exception;
use App\Domain\Model\Route\Modality;
use App\Domain\Model\Route\Segment;

final class ModalityMismatchException extends Exception
{
    public static function fromSegment(Segment $segment, Modality $modality): self
    {
        return new self(
            sprintf(
                'Cannot join segment with id = %s (%s) with modality = %s',
                $segment->id(),
                $segment->modality()->value,
                $modality->value
            )
        );
    }
}
