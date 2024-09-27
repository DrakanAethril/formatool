<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240920085059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE topics_groups DROP FOREIGN KEY FK_2F2CF43FBEFD98D1');
        $this->addSql('DROP INDEX IDX_2F2CF43FBEFD98D1 ON topics_groups');
        $this->addSql('ALTER TABLE topics_groups DROP training_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE topics_groups ADD training_id INT NOT NULL');
        $this->addSql('ALTER TABLE topics_groups ADD CONSTRAINT FK_2F2CF43FBEFD98D1 FOREIGN KEY (training_id) REFERENCES trainings (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_2F2CF43FBEFD98D1 ON topics_groups (training_id)');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
