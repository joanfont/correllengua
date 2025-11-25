<?php

namespace App\Application\Service\CSV;

use Iterator;

interface CSVReader
{
    public function readLine(): Iterator;
}
