<?php

namespace App\Tests\Unit\Application\Command\Participant;

use App\Application\Command\Participant\CreateParticipant;
use App\Domain\Exception\Participant\ParticipantAlreadyExistsException;
use App\Domain\Model\Participant\Participant;
use App\Domain\Repository\Participant\ParticipantRepository;
use App\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Ramsey\Uuid\Uuid;

class CreateParticipantTest extends TestCase
{
    private readonly ParticipantRepository&MockObject $participantRepository;

    protected function setUp(): void
    {
        $this->participantRepository = $this->createMock(ParticipantRepository::class);
        self::set(ParticipantRepository::class, $this->participantRepository);
    }

    public function testCreatesParticipant(): void
    {
        $this->participantRepository
            ->expects($this->once())
            ->method('existsByEmail')
            ->with('foo@bar.com')
            ->willReturn(false);

        $this->participantRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function (Participant $participant): bool {
                return Uuid::isValid($participant->id())
                    && 'foo@bar.com' === $participant->email()
                    && 'foo' === $participant->name()
                    && 'bar' === $participant->surname();
            }));

        $createParticipant = new CreateParticipant(
            name: 'foo',
            surname: 'bar',
            email: 'foo@bar.com',
        );

        self::handleCommand($createParticipant);
    }

    public function testParticipantAlreadyExists(): void
    {
        $this->participantRepository
            ->expects($this->once())
            ->method('existsByEmail')
            ->with('foo@bar.com')
            ->willReturn(true);

        $this->participantRepository
            ->expects($this->never())
            ->method('add');

        static::expectException(ParticipantAlreadyExistsException::class);

        $createParticipant = new CreateParticipant(
            name: 'foo',
            surname: 'bar',
            email: 'foo@bar.com',
        );

        self::handleCommand($createParticipant);
    }
}
