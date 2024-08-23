<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240823121857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_trainings_trainings_options (users_trainings_id INT NOT NULL, trainings_options_id INT NOT NULL, INDEX IDX_83819B60455F84F3 (users_trainings_id), INDEX IDX_83819B60E0C01927 (trainings_options_id), PRIMARY KEY(users_trainings_id, trainings_options_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE users_trainings_trainings_options ADD CONSTRAINT FK_83819B60455F84F3 FOREIGN KEY (users_trainings_id) REFERENCES users_trainings (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_trainings_trainings_options ADD CONSTRAINT FK_83819B60E0C01927 FOREIGN KEY (trainings_options_id) REFERENCES trainings_options (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users_trainings_trainings_options DROP FOREIGN KEY FK_83819B60455F84F3');
        $this->addSql('ALTER TABLE users_trainings_trainings_options DROP FOREIGN KEY FK_83819B60E0C01927');
        $this->addSql('DROP TABLE users_trainings_trainings_options');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
