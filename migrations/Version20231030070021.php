<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231030070021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE time_slots_types ADD color VARCHAR(20) NOT NULL');

        $this->addSql("INSERT INTO `time_slots_types` (`id`, `name`, `inactive`, `color`) VALUES
        (1, 'Inscriptions', NULL, '#4299E1'),
        (2, 'Cours', NULL, '#206BC4'),
        (3, 'Entreprise', NULL, '#D6336C'),
        (4, 'Vacances', NULL, '#17A2B8'),
        (5, 'Libéré', NULL, '#6C7A91'),
        (6, 'Soutenances', NULL, '#F76707');");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE time_slots_types DROP color');
    }
}
