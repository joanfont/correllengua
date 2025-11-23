<?php

namespace App\Domain\Exception\Route;

use App\Domain\Exception\NotFoundException;
use App\Domain\Model\Route\RouteId;

final class RouteNotFoundException extends NotFoundException
{
    public static function fromId(RouteId $id): self
    {
        return new self(\sprintf('Route with id = %s not found', $id));
    }

    public static function fromCode(int $code): self
    {
        return new self(\sprintf('Route with code = %s not found', $code));
    }
}
