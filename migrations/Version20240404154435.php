<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240404154435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lesson_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, inactive DATETIME DEFAULT NULL, agenda_color VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE lesson_sessions ADD lesson_type_id INT DEFAULT NULL, DROP unsupervised');
        $this->addSql('ALTER TABLE lesson_sessions ADD CONSTRAINT FK_D227D70A3030DE34 FOREIGN KEY (lesson_type_id) REFERENCES lesson_types (id)');
        $this->addSql('CREATE INDEX IDX_D227D70A3030DE34 ON lesson_sessions (lesson_type_id)');
        $this->addSql('ALTER TABLE trainings DROP FOREIGN KEY FK_66DC43307E3C61F9');
        $this->addSql('DROP INDEX IDX_66DC43307E3C61F9 ON trainings');
        $this->addSql('ALTER TABLE trainings ADD activate_financial_management TINYINT(1) DEFAULT NULL, ADD scholarship_contact_id INT DEFAULT NULL, ADD administrative_contact_id INT DEFAULT NULL, ADD default_class_room_id INT DEFAULT NULL, CHANGE owner_id content_contact_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trainings ADD CONSTRAINT FK_66DC43307E9893B8 FOREIGN KEY (content_contact_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE trainings ADD CONSTRAINT FK_66DC433045DF8849 FOREIGN KEY (scholarship_contact_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE trainings ADD CONSTRAINT FK_66DC433034C97303 FOREIGN KEY (administrative_contact_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE trainings ADD CONSTRAINT FK_66DC4330A7438B7C FOREIGN KEY (default_class_room_id) REFERENCES class_rooms (id)');
        $this->addSql('CREATE INDEX IDX_66DC43307E9893B8 ON trainings (content_contact_id)');
        $this->addSql('CREATE INDEX IDX_66DC433045DF8849 ON trainings (scholarship_contact_id)');
        $this->addSql('CREATE INDEX IDX_66DC433034C97303 ON trainings (administrative_contact_id)');
        $this->addSql('CREATE INDEX IDX_66DC4330A7438B7C ON trainings (default_class_room_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lesson_types');
        $this->addSql('ALTER TABLE trainings DROP FOREIGN KEY FK_66DC43307E9893B8');
        $this->addSql('ALTER TABLE trainings DROP FOREIGN KEY FK_66DC433045DF8849');
        $this->addSql('ALTER TABLE trainings DROP FOREIGN KEY FK_66DC433034C97303');
        $this->addSql('ALTER TABLE trainings DROP FOREIGN KEY FK_66DC4330A7438B7C');
        $this->addSql('DROP INDEX IDX_66DC43307E9893B8 ON trainings');
        $this->addSql('DROP INDEX IDX_66DC433045DF8849 ON trainings');
        $this->addSql('DROP INDEX IDX_66DC433034C97303 ON trainings');
        $this->addSql('DROP INDEX IDX_66DC4330A7438B7C ON trainings');
        $this->addSql('ALTER TABLE trainings ADD owner_id INT DEFAULT NULL, DROP activate_financial_management, DROP content_contact_id, DROP scholarship_contact_id, DROP administrative_contact_id, DROP default_class_room_id');
        $this->addSql('ALTER TABLE trainings ADD CONSTRAINT FK_66DC43307E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_66DC43307E3C61F9 ON trainings (owner_id)');
        $this->addSql('ALTER TABLE lesson_sessions DROP FOREIGN KEY FK_D227D70A3030DE34');
        $this->addSql('DROP INDEX IDX_D227D70A3030DE34 ON lesson_sessions');
        $this->addSql('ALTER TABLE lesson_sessions ADD unsupervised TINYINT(1) DEFAULT 0 NOT NULL, DROP lesson_type_id');
    }
}
