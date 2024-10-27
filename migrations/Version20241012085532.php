<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241012085532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE skills (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, cursus_order INT DEFAULT NULL, short_name VARCHAR(255) DEFAULT NULL, inactive DATETIME DEFAULT NULL, topics_group_id INT NOT NULL, INDEX IDX_D53116706C60EBC1 (topics_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE skills_lesson_sessions (skills_id INT NOT NULL, lesson_sessions_id INT NOT NULL, INDEX IDX_9414CCF57FF61858 (skills_id), INDEX IDX_9414CCF5A5A687 (lesson_sessions_id), PRIMARY KEY(skills_id, lesson_sessions_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE skills ADD CONSTRAINT FK_D53116706C60EBC1 FOREIGN KEY (topics_group_id) REFERENCES topics_groups (id)');
        $this->addSql('ALTER TABLE skills_lesson_sessions ADD CONSTRAINT FK_9414CCF57FF61858 FOREIGN KEY (skills_id) REFERENCES skills (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skills_lesson_sessions ADD CONSTRAINT FK_9414CCF5A5A687 FOREIGN KEY (lesson_sessions_id) REFERENCES lesson_sessions (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE skills DROP FOREIGN KEY FK_D53116706C60EBC1');
        $this->addSql('ALTER TABLE skills_lesson_sessions DROP FOREIGN KEY FK_9414CCF57FF61858');
        $this->addSql('ALTER TABLE skills_lesson_sessions DROP FOREIGN KEY FK_9414CCF5A5A687');
        $this->addSql('DROP TABLE skills');
        $this->addSql('DROP TABLE skills_lesson_sessions');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
