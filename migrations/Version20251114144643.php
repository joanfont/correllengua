<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251114144643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participant (id VARCHAR(36) NOT NULL, name VARCHAR(128) NOT NULL, surname VARCHAR(128) NOT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D79F6B11E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE route (id VARCHAR(36) NOT NULL, name VARCHAR(128) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE segment (id VARCHAR(36) NOT NULL, route_id VARCHAR(36) NOT NULL, position INT NOT NULL, capacity INT NOT NULL, transport_mode VARCHAR(5) NOT NULL, start_latitude DOUBLE PRECISION NOT NULL, start_longitude DOUBLE PRECISION NOT NULL, INDEX IDX_1881F56534ECB4E6 (route_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE segment ADD CONSTRAINT FK_1881F56534ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE segment DROP FOREIGN KEY FK_1881F56534ECB4E6');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE route');
        $this->addSql('DROP TABLE segment');
    }
}
