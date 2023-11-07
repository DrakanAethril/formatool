<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231107131021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lesson_sessions (id INT AUTO_INCREMENT NOT NULL, training_id INT NOT NULL, topic_id INT NOT NULL, teacher_id INT DEFAULT NULL, day DATE NOT NULL, start_hour TIME NOT NULL, end_hour TIME NOT NULL, length INT NOT NULL, INDEX IDX_D227D70ABEFD98D1 (training_id), INDEX IDX_D227D70A1F55203D (topic_id), INDEX IDX_D227D70A41807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lesson_sessions ADD CONSTRAINT FK_D227D70ABEFD98D1 FOREIGN KEY (training_id) REFERENCES trainings (id)');
        $this->addSql('ALTER TABLE lesson_sessions ADD CONSTRAINT FK_D227D70A1F55203D FOREIGN KEY (topic_id) REFERENCES topics_trainings (id)');
        $this->addSql('ALTER TABLE lesson_sessions ADD CONSTRAINT FK_D227D70A41807E1D FOREIGN KEY (teacher_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesson_sessions DROP FOREIGN KEY FK_D227D70ABEFD98D1');
        $this->addSql('ALTER TABLE lesson_sessions DROP FOREIGN KEY FK_D227D70A1F55203D');
        $this->addSql('ALTER TABLE lesson_sessions DROP FOREIGN KEY FK_D227D70A41807E1D');
        $this->addSql('DROP TABLE lesson_sessions');
    }
}
