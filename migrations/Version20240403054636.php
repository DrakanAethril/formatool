<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240403054636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE class_rooms_trainings DROP FOREIGN KEY FK_FFB07963AEDA3FB');
        $this->addSql('ALTER TABLE class_rooms_trainings DROP FOREIGN KEY FK_FFB0796161BA2FF');
        $this->addSql('DROP TABLE class_rooms_trainings');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE class_rooms_trainings (class_rooms_id INT NOT NULL, trainings_id INT NOT NULL, INDEX IDX_FFB07963AEDA3FB (class_rooms_id), INDEX IDX_FFB0796161BA2FF (trainings_id), PRIMARY KEY(class_rooms_id, trainings_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE class_rooms_trainings ADD CONSTRAINT FK_FFB07963AEDA3FB FOREIGN KEY (class_rooms_id) REFERENCES class_rooms (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE class_rooms_trainings ADD CONSTRAINT FK_FFB0796161BA2FF FOREIGN KEY (trainings_id) REFERENCES trainings (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
