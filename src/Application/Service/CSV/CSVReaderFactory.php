<?php

namespace App\Application\Service\CSV;

interface CSVReaderFactory
{
    public function makeFromString(string $data): CSVReader;
}
