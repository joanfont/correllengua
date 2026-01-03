<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251123183139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE registration ADD hash VARCHAR(128) NOT NULL');
        $this->addSql('CREATE INDEX hash ON registration (hash)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX hash ON registration');
        $this->addSql('ALTER TABLE registration DROP hash');
    }
}
