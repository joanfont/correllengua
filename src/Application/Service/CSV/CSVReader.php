<?php

declare(strict_types=1);

namespace App\Application\Service\CSV;

use Iterator;

interface CSVReader
{
    public function readLine(): Iterator;
}
