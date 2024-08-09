<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240809145457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lesson_sessions_trainings_options (lesson_sessions_id INT NOT NULL, trainings_options_id INT NOT NULL, INDEX IDX_ABAA678FA5A687 (lesson_sessions_id), INDEX IDX_ABAA678FE0C01927 (trainings_options_id), PRIMARY KEY(lesson_sessions_id, trainings_options_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE lesson_sessions_trainings_options ADD CONSTRAINT FK_ABAA678FA5A687 FOREIGN KEY (lesson_sessions_id) REFERENCES lesson_sessions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lesson_sessions_trainings_options ADD CONSTRAINT FK_ABAA678FE0C01927 FOREIGN KEY (trainings_options_id) REFERENCES trainings_options (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesson_sessions_trainings_options DROP FOREIGN KEY FK_ABAA678FA5A687');
        $this->addSql('ALTER TABLE lesson_sessions_trainings_options DROP FOREIGN KEY FK_ABAA678FE0C01927');
        $this->addSql('DROP TABLE lesson_sessions_trainings_options');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
