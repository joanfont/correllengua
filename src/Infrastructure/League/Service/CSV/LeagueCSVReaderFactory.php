<?php

namespace App\Infrastructure\League\Service\CSV;

use App\Application\Service\CSV\CSVReader;
use App\Application\Service\CSV\CSVReaderFactory;
use App\Domain\Exception\CSV\InvalidSourceException;
use League\Csv\Reader;
use League\Csv\UnavailableStream;

class LeagueCSVReaderFactory implements CSVReaderFactory
{
    public function makeFromFile(string $path): CSVReader
    {
        try {
            $reader = Reader::from($path);

            if (0 === $reader->count()) {
                throw new InvalidSourceException();
            }
        } catch (UnavailableStream $ex) {
            throw new InvalidSourceException($ex->getMessage(), $ex);
        }

        return new LeagueCSVReader($reader);
    }
}
