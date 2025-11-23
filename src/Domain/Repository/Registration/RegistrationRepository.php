<?php

namespace App\Domain\Repository\Registration;

use App\Domain\Model\Registration\Registration;
use App\Domain\Model\Registration\RegistrationId;

interface RegistrationRepository
{
    public function findById(RegistrationId $id): Registration;

    public function findByHash(string $hash): Registration;

    public function delete(Registration $registration): void;
}
