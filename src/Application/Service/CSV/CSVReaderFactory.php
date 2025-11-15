<?php

namespace App\Application\Service\CSV;

interface CSVReaderFactory
{
    public function makeFromFile(string $path): CSVReader;
}