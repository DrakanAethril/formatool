<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402135235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trainings_modality (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, inactive DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE trainings ADD trainings_modality_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trainings ADD CONSTRAINT FK_66DC43304207E466 FOREIGN KEY (trainings_modality_id) REFERENCES trainings_modality (id)');
        $this->addSql('CREATE INDEX IDX_66DC43304207E466 ON trainings (trainings_modality_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE trainings_modality');
        $this->addSql('ALTER TABLE trainings DROP FOREIGN KEY FK_66DC43304207E466');
        $this->addSql('DROP INDEX IDX_66DC43304207E466 ON trainings');
        $this->addSql('ALTER TABLE trainings DROP trainings_modality_id');
    }
}
