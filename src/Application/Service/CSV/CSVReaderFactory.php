<?php

declare(strict_types=1);

namespace App\Application\Service\CSV;

interface CSVReaderFactory
{
    public function makeFromString(string $data): CSVReader;
}
