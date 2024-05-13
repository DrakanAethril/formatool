<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240503135405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_places (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(50) NOT NULL, roles JSON NOT NULL, permissions LONGTEXT DEFAULT NULL, user_id INT NOT NULL, place_id INT NOT NULL, INDEX IDX_F148E2C5A76ED395 (user_id), INDEX IDX_F148E2C5DA6A219 (place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE users_trainings (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(50) NOT NULL, role JSON NOT NULL, perms LONGTEXT DEFAULT NULL, user_id INT NOT NULL, training_id INT NOT NULL, INDEX IDX_A139D1A76ED395 (user_id), INDEX IDX_A139D1BEFD98D1 (training_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE users_places ADD CONSTRAINT FK_F148E2C5A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users_places ADD CONSTRAINT FK_F148E2C5DA6A219 FOREIGN KEY (place_id) REFERENCES places (id)');
        $this->addSql('ALTER TABLE users_trainings ADD CONSTRAINT FK_A139D1A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users_trainings ADD CONSTRAINT FK_A139D1BEFD98D1 FOREIGN KEY (training_id) REFERENCES trainings (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users_places DROP FOREIGN KEY FK_F148E2C5A76ED395');
        $this->addSql('ALTER TABLE users_places DROP FOREIGN KEY FK_F148E2C5DA6A219');
        $this->addSql('ALTER TABLE users_trainings DROP FOREIGN KEY FK_A139D1A76ED395');
        $this->addSql('ALTER TABLE users_trainings DROP FOREIGN KEY FK_A139D1BEFD98D1');
        $this->addSql('DROP TABLE users_places');
        $this->addSql('DROP TABLE users_trainings');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
