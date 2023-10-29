<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231029192649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE time_slots (id INT AUTO_INCREMENT NOT NULL, training_id INT NOT NULL, time_slots_types_id INT NOT NULL, name VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, inactive DATETIME DEFAULT NULL, INDEX IDX_8D06D4ACBEFD98D1 (training_id), INDEX IDX_8D06D4ACE14B4D15 (time_slots_types_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE time_slots_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, inactive DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE time_slots ADD CONSTRAINT FK_8D06D4ACBEFD98D1 FOREIGN KEY (training_id) REFERENCES trainings (id)');
        $this->addSql('ALTER TABLE time_slots ADD CONSTRAINT FK_8D06D4ACE14B4D15 FOREIGN KEY (time_slots_types_id) REFERENCES time_slots_types (id)');

        $this->addSql("INSERT INTO `time_slots_types` (`id`, `name`, `inactive`) VALUES
        (1, 'Inscriptions', NULL),
        (2, 'Cours', NULL),
        (3, 'Entreprise', NULL),
        (4, 'Vacances', NULL),
        (5, 'Libéré', NULL),
        (6, 'Soutenances', NULL);");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE time_slots DROP FOREIGN KEY FK_8D06D4ACBEFD98D1');
        $this->addSql('ALTER TABLE time_slots DROP FOREIGN KEY FK_8D06D4ACE14B4D15');
        $this->addSql('DROP TABLE time_slots');
        $this->addSql('DROP TABLE time_slots_types');
    }
}
