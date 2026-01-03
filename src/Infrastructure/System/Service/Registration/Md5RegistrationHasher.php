<?php

declare(strict_types=1);

namespace App\Infrastructure\System\Service\Registration;

use App\Application\Service\Registration\RegistrationHasher;
use App\Domain\Model\Registration\RegistrationId;

use function md5;

class Md5RegistrationHasher implements RegistrationHasher
{
    public function hash(RegistrationId $id): string
    {
        return md5((string) $id);
    }
}
