<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240401195503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cursus (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, inactive DATETIME DEFAULT NULL, type_id INT NOT NULL, INDEX IDX_255A0C3C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE cursus_type (id INT AUTO_INCREMENT NOT NULL, level VARCHAR(255) NOT NULL, inactive DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE cursus ADD CONSTRAINT FK_255A0C3C54C8C93 FOREIGN KEY (type_id) REFERENCES cursus_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cursus DROP FOREIGN KEY FK_255A0C3C54C8C93');
        $this->addSql('DROP TABLE cursus');
        $this->addSql('DROP TABLE cursus_type');
    }
}
