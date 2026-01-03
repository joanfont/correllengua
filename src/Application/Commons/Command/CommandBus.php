<?php

declare(strict_types=1);

namespace App\Application\Commons\Command;

interface CommandBus
{
    public function dispatch(Command $command): void;
}
