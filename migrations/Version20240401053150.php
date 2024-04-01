<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240401053150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE class_rooms_trainings (class_rooms_id INT NOT NULL, trainings_id INT NOT NULL, INDEX IDX_FFB07963AEDA3FB (class_rooms_id), INDEX IDX_FFB0796161BA2FF (trainings_id), PRIMARY KEY(class_rooms_id, trainings_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE class_rooms_trainings ADD CONSTRAINT FK_FFB07963AEDA3FB FOREIGN KEY (class_rooms_id) REFERENCES class_rooms (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE class_rooms_trainings ADD CONSTRAINT FK_FFB0796161BA2FF FOREIGN KEY (trainings_id) REFERENCES trainings (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lesson_sessions ADD class_rooms_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lesson_sessions ADD CONSTRAINT FK_D227D70A3AEDA3FB FOREIGN KEY (class_rooms_id) REFERENCES class_rooms (id)');
        $this->addSql('CREATE INDEX IDX_D227D70A3AEDA3FB ON lesson_sessions (class_rooms_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE class_rooms_trainings DROP FOREIGN KEY FK_FFB07963AEDA3FB');
        $this->addSql('ALTER TABLE class_rooms_trainings DROP FOREIGN KEY FK_FFB0796161BA2FF');
        $this->addSql('DROP TABLE class_rooms_trainings');
        $this->addSql('ALTER TABLE lesson_sessions DROP FOREIGN KEY FK_D227D70A3AEDA3FB');
        $this->addSql('DROP INDEX IDX_D227D70A3AEDA3FB ON lesson_sessions');
        $this->addSql('ALTER TABLE lesson_sessions DROP class_rooms_id');
    }
}
