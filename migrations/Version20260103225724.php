<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260103225724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE press_note ADD author_id VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE press_note ADD CONSTRAINT FK_571160A9F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_571160A9F675F31B ON press_note (author_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE press_note DROP FOREIGN KEY FK_571160A9F675F31B');
        $this->addSql('DROP INDEX UNIQ_571160A9F675F31B ON press_note');
        $this->addSql('ALTER TABLE press_note DROP author_id');
    }
}
