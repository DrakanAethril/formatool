<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231019091129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE topics_trainings_label_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE topics_trainings_label (id INT NOT NULL, name VARCHAR(255) NOT NULL, inactive TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE topics_trainings_label_topics_trainings (topics_trainings_label_id INT NOT NULL, topics_trainings_id INT NOT NULL, PRIMARY KEY(topics_trainings_label_id, topics_trainings_id))');
        $this->addSql('CREATE INDEX IDX_79E70FB4984173 ON topics_trainings_label_topics_trainings (topics_trainings_label_id)');
        $this->addSql('CREATE INDEX IDX_79E70F5CED7B79 ON topics_trainings_label_topics_trainings (topics_trainings_id)');
        $this->addSql('ALTER TABLE topics_trainings_label_topics_trainings ADD CONSTRAINT FK_79E70FB4984173 FOREIGN KEY (topics_trainings_label_id) REFERENCES topics_trainings_label (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE topics_trainings_label_topics_trainings ADD CONSTRAINT FK_79E70F5CED7B79 FOREIGN KEY (topics_trainings_id) REFERENCES topics_trainings (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE topics_trainings_label_id_seq CASCADE');
        $this->addSql('ALTER TABLE topics_trainings_label_topics_trainings DROP CONSTRAINT FK_79E70FB4984173');
        $this->addSql('ALTER TABLE topics_trainings_label_topics_trainings DROP CONSTRAINT FK_79E70F5CED7B79');
        $this->addSql('DROP TABLE topics_trainings_label');
        $this->addSql('DROP TABLE topics_trainings_label_topics_trainings');
    }
}
