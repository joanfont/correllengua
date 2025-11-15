<?php

namespace App\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;

abstract class DoctrineRepository
{
    public function __construct(protected readonly EntityManagerInterface $entityManager)
    {
    }
}