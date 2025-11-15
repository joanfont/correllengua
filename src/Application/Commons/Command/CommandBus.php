<?php

namespace App\Application\Commons\Command;

interface CommandBus
{
    public function dispatch(Command $command): void;
}
