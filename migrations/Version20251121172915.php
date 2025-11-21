<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251121172915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file (id VARCHAR(36) NOT NULL, name VARCHAR(128) NOT NULL, path VARCHAR(512) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE press_note (id VARCHAR(36) NOT NULL, image_id VARCHAR(36) DEFAULT NULL, title VARCHAR(255) NOT NULL, subtitle VARCHAR(255) NOT NULL, body VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_571160A93DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE press_note ADD CONSTRAINT FK_571160A93DA5256D FOREIGN KEY (image_id) REFERENCES file (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE press_note DROP FOREIGN KEY FK_571160A93DA5256D');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE press_note');
    }
}
