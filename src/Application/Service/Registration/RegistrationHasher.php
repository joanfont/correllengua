<?php

namespace App\Application\Service\Registration;

use App\Domain\Model\Registration\RegistrationId;

interface RegistrationHasher
{
    public function hash(RegistrationId $id): string;
}
