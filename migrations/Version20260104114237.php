<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260104114237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE press_note DROP INDEX UNIQ_571160A9F675F31B, ADD INDEX IDX_571160A9F675F31B (author_id)');
        $this->addSql('ALTER TABLE press_note DROP INDEX UNIQ_571160A93DA5256D, ADD INDEX IDX_571160A93DA5256D (image_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE press_note DROP INDEX IDX_571160A9F675F31B, ADD UNIQUE INDEX UNIQ_571160A9F675F31B (author_id)');
        $this->addSql('ALTER TABLE press_note DROP INDEX IDX_571160A93DA5256D, ADD UNIQUE INDEX UNIQ_571160A93DA5256D (image_id)');
    }
}
