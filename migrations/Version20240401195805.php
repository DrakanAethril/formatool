<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240401195805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trainings ADD cursus_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trainings ADD CONSTRAINT FK_66DC433040AEF4B9 FOREIGN KEY (cursus_id) REFERENCES cursus (id)');
        $this->addSql('CREATE INDEX IDX_66DC433040AEF4B9 ON trainings (cursus_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trainings DROP FOREIGN KEY FK_66DC433040AEF4B9');
        $this->addSql('DROP INDEX IDX_66DC433040AEF4B9 ON trainings');
        $this->addSql('ALTER TABLE trainings DROP cursus_id');
    }
}
