<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241108142528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE skills ADD teacher_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE skills ADD CONSTRAINT FK_D531167041807E1D FOREIGN KEY (teacher_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_D531167041807E1D ON skills (teacher_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE skills DROP FOREIGN KEY FK_D531167041807E1D');
        $this->addSql('DROP INDEX IDX_D531167041807E1D ON skills');
        $this->addSql('ALTER TABLE skills DROP teacher_id');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
