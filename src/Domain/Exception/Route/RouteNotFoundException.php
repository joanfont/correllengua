<?php

namespace App\Domain\Exception\Route;

use App\Domain\Exception\NotFoundException;
use App\Domain\Model\Route\RouteId;

use function sprintf;

final class RouteNotFoundException extends NotFoundException
{
    public static function fromId(RouteId $id): self
    {
        return new self(sprintf('Route with id = %s not found', $id));
    }

    public static function fromName(string $name): self
    {
        return new self(sprintf('Route with name = %s not found', $name));
    }
}
