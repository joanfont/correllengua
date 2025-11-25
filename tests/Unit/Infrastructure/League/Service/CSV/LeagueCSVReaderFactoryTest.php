<?php

namespace App\Tests\Unit\Infrastructure\League\Service\CSV;

use App\Domain\Exception\CSV\InvalidSourceException;
use App\Infrastructure\League\Service\CSV\LeagueCSVReaderFactory;
use App\Tests\TestCase;

class LeagueCSVReaderFactoryTest extends TestCase
{
    public function testMakeFromStringReturnsCsvReaderWithMappedRows(): void
    {
        $csv = "col1,col2\nfoo,1\nbar,2\n";

        $factory = new LeagueCSVReaderFactory();

        $reader = $factory->makeFromString($csv);

        static::assertInstanceOf(\App\Application\Service\CSV\CSVReader::class, $reader);

        $collected = [];
        foreach ($reader->readLine() as $row) {
            $collected[] = $row;
        }

        static::assertCount(2, $collected);
        static::assertSame(['col1' => 'foo', 'col2' => '1'], $collected[0]);
        static::assertSame(['col1' => 'bar', 'col2' => '2'], $collected[1]);
    }

    public function testMakeFromStringThrowsWhenSourceIsEmpty(): void
    {
        $this->expectException(InvalidSourceException::class);

        $factory = new LeagueCSVReaderFactory();

        $factory->makeFromString('');
    }
}
