<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251115120319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE registration (id VARCHAR(36) NOT NULL, participant_id VARCHAR(36) DEFAULT NULL, segment_id VARCHAR(36) DEFAULT NULL, INDEX IDX_62A8A7A79D1C3019 (participant_id), INDEX IDX_62A8A7A7DB296AAD (segment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A79D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id)');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A7DB296AAD FOREIGN KEY (segment_id) REFERENCES segment (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A79D1C3019');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A7DB296AAD');
        $this->addSql('DROP TABLE registration');
    }
}
