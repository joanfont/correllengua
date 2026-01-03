<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Registration;

use App\Application\Command\Registration\DeregisterParticipant;
use App\Domain\Model\Registration\Registration;
use App\Domain\Repository\Registration\RegistrationRepository;
use App\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class DeregisterParticipantHandlerTest extends TestCase
{
    private RegistrationRepository&MockObject $registrationRepository;

    protected function setUp(): void
    {
        $this->registrationRepository = $this->createMock(RegistrationRepository::class);

        self::set(RegistrationRepository::class, $this->registrationRepository);
    }

    public function testInvokeDeletesFoundRegistration(): void
    {
        $hash = 'test-hash-123';

        $registration = $this->createMock(Registration::class);

        $this->registrationRepository
            ->expects($this->once())
            ->method('findByHash')
            ->with($this->equalTo($hash))
            ->willReturn($registration);

        $this->registrationRepository
            ->expects($this->once())
            ->method('delete')
            ->with($this->identicalTo($registration));

        $command = new DeregisterParticipant($hash);

        self::handleCommand($command);
    }
}
