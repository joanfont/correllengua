<?php

namespace App\Domain\Model;

abstract class Entity
{
    protected \DateTimeInterface $createdAt;
    protected \DateTimeInterface $updatedAt;

    public function created(): void
    {
        $this->createdAt = new \DateTime();
    }

    public function updated(): void
    {
        $this->updatedAt = new \DateTime();
    }
}
