<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Query\Participant;

use App\Application\Query\Participant\GetParticipantByEmail;
use App\Domain\DTO\Participant\Participant;
use App\Domain\Provider\Participant\ParticipantProvider;
use App\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class GetParticipantByEmailTest extends TestCase
{
    private readonly ParticipantProvider&MockObject $participantProvider;

    protected function setUp(): void
    {
        $this->participantProvider = $this->createMock(ParticipantProvider::class);

        self::set(ParticipantProvider::class, $this->participantProvider);
    }

    public function testReturnsParticipantByEmail(): void
    {
        $email = 'john.doe@example.com';
        $participant = new Participant(
            id: 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
            name: 'John',
            surname: 'Doe',
            email: $email,
        );

        $this->participantProvider
            ->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($participant);

        $query = new GetParticipantByEmail($email);

        $result = self::handleQuery($query);

        self::assertInstanceOf(Participant::class, $result);
        self::assertSame($participant->id, $result->id);
        self::assertSame($participant->name, $result->name);
        self::assertSame($participant->surname, $result->surname);
        self::assertSame($participant->email, $result->email);
    }
}
