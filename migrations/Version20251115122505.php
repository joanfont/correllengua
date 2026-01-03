<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251115122505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE registration ADD modality VARCHAR(5) NOT NULL');
        $this->addSql('ALTER TABLE segment CHANGE transport_mode modality VARCHAR(5) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE segment CHANGE modality transport_mode VARCHAR(5) NOT NULL');
        $this->addSql('ALTER TABLE registration DROP modality');
    }
}
