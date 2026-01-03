<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251124182302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE itinerary (id VARCHAR(36) NOT NULL, route_id VARCHAR(36) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, name VARCHAR(128) NOT NULL, INDEX IDX_FF2238F634ECB4E6 (route_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE itinerary ADD CONSTRAINT FK_FF2238F634ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id)');
        $this->addSql('ALTER TABLE segment DROP FOREIGN KEY FK_1881F56534ECB4E6');
        $this->addSql('DROP INDEX IDX_1881F56534ECB4E6 ON segment');
        $this->addSql('ALTER TABLE segment CHANGE route_id itinerary_id VARCHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT FK_1881F56515F737B2 FOREIGN KEY (itinerary_id) REFERENCES itinerary (id)');
        $this->addSql('CREATE INDEX IDX_1881F56515F737B2 ON segment (itinerary_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE segment DROP FOREIGN KEY FK_1881F56515F737B2');
        $this->addSql('ALTER TABLE itinerary DROP FOREIGN KEY FK_FF2238F634ECB4E6');
        $this->addSql('DROP TABLE itinerary');
        $this->addSql('DROP INDEX IDX_1881F56515F737B2 ON segment');
        $this->addSql('ALTER TABLE segment CHANGE itinerary_id route_id VARCHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT FK_1881F56534ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_1881F56534ECB4E6 ON segment (route_id)');
    }
}
