<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240809133327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trainings_options (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, inactive DATETIME DEFAULT NULL, training_id INT NOT NULL, INDEX IDX_27DD79DABEFD98D1 (training_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE trainings_options ADD CONSTRAINT FK_27DD79DABEFD98D1 FOREIGN KEY (training_id) REFERENCES trainings (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trainings_options DROP FOREIGN KEY FK_27DD79DABEFD98D1');
        $this->addSql('DROP TABLE trainings_options');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
