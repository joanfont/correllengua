<?php

namespace App\Domain\Provider\Registration;

use App\Domain\DTO\Registration\Registration;

interface RegistrationProvider
{
    public function findById(string $id): Registration;
}
