<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251116110217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE route ADD code INT NOT NULL, ADD description LONGTEXT NOT NULL, ADD starts_at DATE NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2C4207977153098 ON route (code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_2C4207977153098 ON route');
        $this->addSql('ALTER TABLE route DROP code, DROP description, DROP starts_at');
    }
}
