<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231014071147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE topics_trainings (topics_id INT NOT NULL, trainings_id INT NOT NULL, PRIMARY KEY(topics_id, trainings_id))');
        $this->addSql('CREATE INDEX IDX_62955117BF06A414 ON topics_trainings (topics_id)');
        $this->addSql('CREATE INDEX IDX_62955117161BA2FF ON topics_trainings (trainings_id)');
        $this->addSql('ALTER TABLE topics_trainings ADD CONSTRAINT FK_62955117BF06A414 FOREIGN KEY (topics_id) REFERENCES topics (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE topics_trainings ADD CONSTRAINT FK_62955117161BA2FF FOREIGN KEY (trainings_id) REFERENCES trainings (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trainings ADD place_id INT NOT NULL');
        $this->addSql('ALTER TABLE trainings ADD CONSTRAINT FK_66DC4330DA6A219 FOREIGN KEY (place_id) REFERENCES places (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_66DC4330DA6A219 ON trainings (place_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE topics_trainings DROP CONSTRAINT FK_62955117BF06A414');
        $this->addSql('ALTER TABLE topics_trainings DROP CONSTRAINT FK_62955117161BA2FF');
        $this->addSql('DROP TABLE topics_trainings');
        $this->addSql('ALTER TABLE trainings DROP CONSTRAINT FK_66DC4330DA6A219');
        $this->addSql('DROP INDEX IDX_66DC4330DA6A219');
        $this->addSql('ALTER TABLE trainings DROP place_id');
    }
}
