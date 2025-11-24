<?php

namespace App\Infrastructure\Doctrine\Provider;

use Doctrine\ORM\EntityManagerInterface;

abstract class DoctrineProvider
{
    public function __construct(protected readonly EntityManagerInterface $entityManager) {}
}
