<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231017131248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE topics_trainings_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE topics_trainings (id INT NOT NULL, topics_id INT NOT NULL, trainings_id INT NOT NULL, cm INT NOT NULL, td INT NOT NULL, tp INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62955117BF06A414 ON topics_trainings (topics_id)');
        $this->addSql('CREATE INDEX IDX_62955117161BA2FF ON topics_trainings (trainings_id)');
        $this->addSql('ALTER TABLE topics_trainings ADD CONSTRAINT FK_62955117BF06A414 FOREIGN KEY (topics_id) REFERENCES topics (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE topics_trainings ADD CONSTRAINT FK_62955117161BA2FF FOREIGN KEY (trainings_id) REFERENCES trainings (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trainings ADD owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trainings ADD CONSTRAINT FK_66DC43307E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_66DC43307E3C61F9 ON trainings (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE topics_trainings_id_seq CASCADE');
        $this->addSql('ALTER TABLE topics_trainings DROP CONSTRAINT FK_62955117BF06A414');
        $this->addSql('ALTER TABLE topics_trainings DROP CONSTRAINT FK_62955117161BA2FF');
        $this->addSql('DROP TABLE topics_trainings');
        $this->addSql('ALTER TABLE trainings DROP CONSTRAINT FK_66DC43307E3C61F9');
        $this->addSql('DROP INDEX IDX_66DC43307E3C61F9');
        $this->addSql('ALTER TABLE trainings DROP owner_id');
    }
}
