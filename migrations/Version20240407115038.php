<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240407115038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE training_financial_items (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, source INT NOT NULL, type INT NOT NULL, quantity NUMERIC(10, 2) DEFAULT NULL, value NUMERIC(10, 2) NOT NULL, inactive DATETIME DEFAULT NULL, training_id INT NOT NULL, lesson_type_id INT DEFAULT NULL, INDEX IDX_2528BA59BEFD98D1 (training_id), INDEX IDX_2528BA593030DE34 (lesson_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE training_financial_items ADD CONSTRAINT FK_2528BA59BEFD98D1 FOREIGN KEY (training_id) REFERENCES trainings (id)');
        $this->addSql('ALTER TABLE training_financial_items ADD CONSTRAINT FK_2528BA593030DE34 FOREIGN KEY (lesson_type_id) REFERENCES lesson_types (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training_financial_items DROP FOREIGN KEY FK_2528BA59BEFD98D1');
        $this->addSql('ALTER TABLE training_financial_items DROP FOREIGN KEY FK_2528BA593030DE34');
        $this->addSql('DROP TABLE training_financial_items');
    }
}
