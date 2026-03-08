<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Entry-point fixture. All real data lives in the dedicated fixture classes.
 * Load order is handled via DependentFixtureInterface on each class.
 */
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
    }
}
