<?php

namespace App\Tests\Unit\Infrastructure\System\Service\Registration;

use App\Infrastructure\System\Service\Registration\Md5RegistrationHasher;
use App\Domain\Model\Registration\RegistrationId;
use App\Tests\TestCase;

class Md5RegistrationHasherTest extends TestCase
{
    public function testHashMatchesMd5OfIdString(): void
    {
        $id = RegistrationId::generate();

        $hasher = new Md5RegistrationHasher();

        $expected = md5((string) $id);

        $this->assertSame($expected, $hasher->hash($id));
    }

    public function testDifferentIdsProduceDifferentHashes(): void
    {
        $id1 = RegistrationId::from('11111111-1111-1111-1111-111111111111');
        $id2 = RegistrationId::from('22222222-2222-2222-2222-222222222222');

        $hasher = new Md5RegistrationHasher();

        $hash1 = $hasher->hash($id1);
        $hash2 = $hasher->hash($id2);

        $this->assertNotSame((string) $id1, (string) $id2);
        $this->assertNotSame($hash1, $hash2);
    }
}
