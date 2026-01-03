<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Provider;

use Doctrine\ORM\EntityManagerInterface;

abstract class DoctrineProvider
{
    public function __construct(protected readonly EntityManagerInterface $entityManager)
    {
    }
}
