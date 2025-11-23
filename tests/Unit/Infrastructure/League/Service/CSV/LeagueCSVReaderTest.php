<?php

namespace App\Tests\Unit\Infrastructure\League\Service\CSV;

use App\Infrastructure\League\Service\CSV\LeagueCSVReader;
use League\Csv\Reader;
use App\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

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
            ->willReturn(new \ArrayIterator($records));

        $csvReader = new LeagueCSVReader($reader);

        $collected = [];
        foreach ($csvReader->readLine() as $record) {
            $collected[] = $record;
        }

        static::assertCount(2, $collected);
        static::assertSame($records[0], $collected[0]);
        static::assertSame($records[1], $collected[1]);
    }
}

