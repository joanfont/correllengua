<?php

namespace App\Infrastructure\League\Service\CSV;

use App\Application\Service\CSV\CSVReader;
use Iterator;
use League\Csv\Reader;

class LeagueCSVReader implements CSVReader
{
    /**
     * @param Reader<array<string, string>> $reader
     */
    public function __construct(private readonly Reader $reader)
    {
    }

    public function readLine(): Iterator
    {
        yield from $this->reader->getRecords();
    }
}
