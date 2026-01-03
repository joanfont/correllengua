<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\League\Service\CSV;

use App\Infrastructure\League\Service\CSV\LeagueCSVReader;
use App\Tests\TestCase;
use ArrayIterator;
use League\Csv\Reader;

class LeagueCSVReaderTest extends TestCase
{
    public function testReadLineYieldsRecordsFromReader(): void
    {
        $records = [
            ['col1' => 'a', 'col2' => '1'],
            ['col1' => 'b', 'col2' => '2'],
        ];

        $reader = $this->createMock(Reader::class);

        $reader
            ->expects(static::once())
            ->method('getRecords')
            ->willReturn(new ArrayIterator($records));

        $csvReader = new LeagueCSVReader($reader);

        $collected = [];
        foreach ($csvReader->readLine() as $record) {
            $collected[] = $record;
        }

        self::assertCount(2, $collected);
        self::assertSame($records[0], $collected[0]);
        self::assertSame($records[1], $collected[1]);
    }
}
