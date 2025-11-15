<?php

namespace App\Application\Service\CSV;

interface CSVReader
{
    public function readLine(): \Iterator;
}
