<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240501182213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE acl_permissions (id INT AUTO_INCREMENT NOT NULL, subject_id INT NOT NULL, resource VARCHAR(50) NOT NULL, privilege VARCHAR(50) NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_4066EC45A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE acl_permissions ADD CONSTRAINT FK_4066EC45A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE acl_permissions DROP FOREIGN KEY FK_4066EC45A76ED395');
        $this->addSql('DROP TABLE acl_permissions');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
