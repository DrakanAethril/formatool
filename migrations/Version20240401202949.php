<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240401202949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cursus_places (cursus_id INT NOT NULL, places_id INT NOT NULL, INDEX IDX_B98F971E40AEF4B9 (cursus_id), INDEX IDX_B98F971E8317B347 (places_id), PRIMARY KEY(cursus_id, places_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE cursus_places ADD CONSTRAINT FK_B98F971E40AEF4B9 FOREIGN KEY (cursus_id) REFERENCES cursus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cursus_places ADD CONSTRAINT FK_B98F971E8317B347 FOREIGN KEY (places_id) REFERENCES places (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cursus_places DROP FOREIGN KEY FK_B98F971E40AEF4B9');
        $this->addSql('ALTER TABLE cursus_places DROP FOREIGN KEY FK_B98F971E8317B347');
        $this->addSql('DROP TABLE cursus_places');
    }
}
