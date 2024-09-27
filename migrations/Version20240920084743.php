<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240920084743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE topics_groups ADD cursus_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE topics_groups ADD CONSTRAINT FK_2F2CF43F40AEF4B9 FOREIGN KEY (cursus_id) REFERENCES cursus (id)');
        $this->addSql('CREATE INDEX IDX_2F2CF43F40AEF4B9 ON topics_groups (cursus_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE topics_groups DROP FOREIGN KEY FK_2F2CF43F40AEF4B9');
        $this->addSql('DROP INDEX IDX_2F2CF43F40AEF4B9 ON topics_groups');
        $this->addSql('ALTER TABLE topics_groups DROP cursus_id');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
