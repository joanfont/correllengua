<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251115121535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62A8A7A79D1C3019DB296AAD ON registration (participant_id, segment_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_62A8A7A79D1C3019DB296AAD ON registration');
    }
}
