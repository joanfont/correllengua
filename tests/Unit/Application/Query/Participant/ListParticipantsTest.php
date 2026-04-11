<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Query\Participant;

use App\Application\Query\Participant\Admin\ListParticipants;
use App\Domain\DTO\Admin\Participant\Participant;
use App\Domain\DTO\Common\Cursor;
use App\Domain\DTO\Common\PaginatedResult;
use App\Domain\Provider\Participant\Admin\ParticipantProvider;
use App\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ListParticipantsTest extends TestCase
{
    private readonly ParticipantProvider&MockObject $participantProvider;

    protected function setUp(): void
    {
        $this->participantProvider = $this->createMock(ParticipantProvider::class);

        self::set(ParticipantProvider::class, $this->participantProvider);
    }

    public function testReturnsListOfParticipants(): void
    {
        $paginatedResult = new PaginatedResult(
            items: [
                new Participant(id: 'p1', name: 'John', surname: 'Doe', email: 'john@example.com', registrations: []),
                new Participant(id: 'p2', name: 'Jane', surname: 'Smith', email: 'jane@example.com', registrations: []),
            ],
            total: 2,
            nextCursor: null,
        );

        $this->participantProvider
            ->expects($this->once())
            ->method('findAllPaginated')
            ->with(null, null, null, null, 20, null)
            ->willReturn($paginatedResult);

        $result = self::handleQuery(new ListParticipants(
            routeId: null,
            itineraryId: null,
            segmentId: null,
            maxOccupancy: null,
            limit: 20,
            cursor: null,
        ));

        self::assertInstanceOf(PaginatedResult::class, $result);
        self::assertCount(2, $result->items);
        self::assertSame(2, $result->total);
        self::assertNull($result->nextCursor);
    }

    public function testReturnsFilteredParticipantsBySegment(): void
    {
        $segmentId = '550e8400-e29b-41d4-a716-446655440000';

        $paginatedResult = new PaginatedResult(
            items: [
                new Participant(id: 'p1', name: 'John', surname: 'Doe', email: 'john@example.com', registrations: []),
            ],
            total: 1,
            nextCursor: null,
        );

        $this->participantProvider
            ->expects($this->once())
            ->method('findAllPaginated')
            ->with(null, null, $segmentId, null, 20, null)
            ->willReturn($paginatedResult);

        $result = self::handleQuery(new ListParticipants(
            routeId: null,
            itineraryId: null,
            segmentId: $segmentId,
            maxOccupancy: null,
            limit: 20,
            cursor: null,
        ));

        self::assertCount(1, $result->items);
        self::assertSame(1, $result->total);
    }

    public function testReturnsPaginatedResults(): void
    {
        $nextCursor = Cursor::fromValue('some-participant-id');

        $paginatedResult = new PaginatedResult(
            items: [
                new Participant(id: 'p1', name: 'John', surname: 'Doe', email: 'john@example.com', registrations: []),
            ],
            total: 100,
            nextCursor: $nextCursor,
        );

        $this->participantProvider
            ->expects($this->once())
            ->method('findAllPaginated')
            ->with(
                null,
                null,
                null,
                null,
                10,
                $this->callback(fn (?Cursor $c) => null !== $c && 'current-id' === $c->value()),
            )
            ->willReturn($paginatedResult);

        $result = self::handleQuery(new ListParticipants(
            routeId: null,
            itineraryId: null,
            segmentId: null,
            maxOccupancy: null,
            limit: 10,
            cursor: Cursor::fromValue('current-id'),
        ));

        self::assertNotNull($result->nextCursor);
        self::assertSame('some-participant-id', $result->nextCursor->value());
        self::assertSame(100, $result->total);
    }

    public function testReturnsEmptyResultsWhenNoParticipants(): void
    {
        $paginatedResult = new PaginatedResult(items: [], total: 0, nextCursor: null);

        $this->participantProvider
            ->expects($this->once())
            ->method('findAllPaginated')
            ->willReturn($paginatedResult);

        $result = self::handleQuery(new ListParticipants(
            routeId: null,
            itineraryId: null,
            segmentId: null,
            maxOccupancy: null,
            limit: 20,
            cursor: null,
        ));

        self::assertEmpty($result->items);
        self::assertSame(0, $result->total);
    }
}
