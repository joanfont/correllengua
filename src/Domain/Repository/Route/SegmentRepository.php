<?php

namespace App\Domain\Repository\Route;

use App\Domain\Exception\Route\SegmentNotFoundException;
use App\Domain\Model\Route\Segment;
use App\Domain\Model\Route\SegmentId;

interface SegmentRepository
{
    public function add(Segment $segment): void;

    /**
     * @throws SegmentNotFoundException
     */
    public function findById(SegmentId $id): Segment;
}
