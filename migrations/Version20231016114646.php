<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231016114646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE topics_trainings DROP CONSTRAINT fk_62955117bf06a414');
        $this->addSql('ALTER TABLE topics_trainings DROP CONSTRAINT fk_62955117161ba2ff');
        $this->addSql('DROP TABLE topics_trainings');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE topics_trainings (topics_id INT NOT NULL, trainings_id INT NOT NULL, PRIMARY KEY(topics_id, trainings_id))');
        $this->addSql('CREATE INDEX idx_62955117161ba2ff ON topics_trainings (trainings_id)');
        $this->addSql('CREATE INDEX idx_62955117bf06a414 ON topics_trainings (topics_id)');
        $this->addSql('ALTER TABLE topics_trainings ADD CONSTRAINT fk_62955117bf06a414 FOREIGN KEY (topics_id) REFERENCES topics (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE topics_trainings ADD CONSTRAINT fk_62955117161ba2ff FOREIGN KEY (trainings_id) REFERENCES trainings (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
