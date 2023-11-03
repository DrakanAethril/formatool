<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231103063415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE topics_trainings_time_slots (topics_trainings_id INT NOT NULL, time_slots_id INT NOT NULL, INDEX IDX_2D9AB85D5CED7B79 (topics_trainings_id), INDEX IDX_2D9AB85DBA14C497 (time_slots_id), PRIMARY KEY(topics_trainings_id, time_slots_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE topics_trainings_time_slots ADD CONSTRAINT FK_2D9AB85D5CED7B79 FOREIGN KEY (topics_trainings_id) REFERENCES topics_trainings (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE topics_trainings_time_slots ADD CONSTRAINT FK_2D9AB85DBA14C497 FOREIGN KEY (time_slots_id) REFERENCES time_slots (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE topics_trainings_time_slots DROP FOREIGN KEY FK_2D9AB85D5CED7B79');
        $this->addSql('ALTER TABLE topics_trainings_time_slots DROP FOREIGN KEY FK_2D9AB85DBA14C497');
        $this->addSql('DROP TABLE topics_trainings_time_slots');
    }
}
