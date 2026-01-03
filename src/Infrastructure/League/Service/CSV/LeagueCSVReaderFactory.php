<?php

declare(strict_types=1);

namespace App\Infrastructure\League\Service\CSV;

use App\Application\Service\CSV\CSVReader;
use App\Application\Service\CSV\CSVReaderFactory;
use App\Domain\Exception\CSV\InvalidSourceException;
use League\Csv\Reader;

class LeagueCSVReaderFactory implements CSVReaderFactory
{
    public function makeFromString(string $data): CSVReader
    {
        $reader = Reader::fromString($data);
        if (0 === $reader->count()) {
            throw new InvalidSourceException();
        }

        $reader->setHeaderOffset(0);

        return new LeagueCSVReader($reader);
    }
}
