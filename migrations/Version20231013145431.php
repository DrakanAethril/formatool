<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231013145431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE places_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE topics_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE trainings_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE places (id INT NOT NULL, name VARCHAR(255) NOT NULL, inactive TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE topics (id INT NOT NULL, name VARCHAR(255) NOT NULL, inactive TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE trainings (id INT NOT NULL, title VARCHAR(255) NOT NULL, level VARCHAR(255) NOT NULL, inactive TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE places_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE topics_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE trainings_id_seq CASCADE');
        $this->addSql('DROP TABLE places');
        $this->addSql('DROP TABLE topics');
        $this->addSql('DROP TABLE trainings');
    }
}
