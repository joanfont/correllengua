<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251114151627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE segment ADD end_latitude DOUBLE PRECISION NOT NULL, ADD end_longitude DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE segment DROP end_latitude, DROP end_longitude');
    }
}
