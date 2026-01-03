<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251124190412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_2C4207977153098 ON route');
        $this->addSql('ALTER TABLE route DROP code');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE route ADD code INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2C4207977153098 ON route (code)');
    }
}
