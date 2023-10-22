<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231021171757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE topics_groups (id INT AUTO_INCREMENT NOT NULL, training_id INT NOT NULL, name VARCHAR(255) NOT NULL, inactive DATETIME DEFAULT NULL, INDEX IDX_2F2CF43FBEFD98D1 (training_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE topics_groups ADD CONSTRAINT FK_2F2CF43FBEFD98D1 FOREIGN KEY (training_id) REFERENCES trainings (id)');
        $this->addSql('ALTER TABLE topics_trainings ADD topics_groups_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE topics_trainings ADD CONSTRAINT FK_62955117B7E2FE8 FOREIGN KEY (topics_groups_id) REFERENCES topics_groups (id)');
        $this->addSql('CREATE INDEX IDX_62955117B7E2FE8 ON topics_trainings (topics_groups_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE topics_trainings DROP FOREIGN KEY FK_62955117B7E2FE8');
        $this->addSql('ALTER TABLE topics_groups DROP FOREIGN KEY FK_2F2CF43FBEFD98D1');
        $this->addSql('DROP TABLE topics_groups');
        $this->addSql('DROP INDEX IDX_62955117B7E2FE8 ON topics_trainings');
        $this->addSql('ALTER TABLE topics_trainings DROP topics_groups_id');
    }
}
