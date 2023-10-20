<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231020131029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE places (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, inactive DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topics (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, inactive DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topics_trainings (id INT AUTO_INCREMENT NOT NULL, topics_id INT NOT NULL, trainings_id INT NOT NULL, cm INT NOT NULL, td INT NOT NULL, tp INT NOT NULL, INDEX IDX_62955117BF06A414 (topics_id), INDEX IDX_62955117161BA2FF (trainings_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topics_trainings_label (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, inactive DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topics_trainings_label_topics_trainings (topics_trainings_label_id INT NOT NULL, topics_trainings_id INT NOT NULL, INDEX IDX_79E70FB4984173 (topics_trainings_label_id), INDEX IDX_79E70F5CED7B79 (topics_trainings_id), PRIMARY KEY(topics_trainings_label_id, topics_trainings_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trainings (id INT AUTO_INCREMENT NOT NULL, place_id INT NOT NULL, owner_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, level VARCHAR(255) NOT NULL, inactive DATETIME DEFAULT NULL, INDEX IDX_66DC4330DA6A219 (place_id), INDEX IDX_66DC43307E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, deleted DATETIME DEFAULT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE topics_trainings ADD CONSTRAINT FK_62955117BF06A414 FOREIGN KEY (topics_id) REFERENCES topics (id)');
        $this->addSql('ALTER TABLE topics_trainings ADD CONSTRAINT FK_62955117161BA2FF FOREIGN KEY (trainings_id) REFERENCES trainings (id)');
        $this->addSql('ALTER TABLE topics_trainings_label_topics_trainings ADD CONSTRAINT FK_79E70FB4984173 FOREIGN KEY (topics_trainings_label_id) REFERENCES topics_trainings_label (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE topics_trainings_label_topics_trainings ADD CONSTRAINT FK_79E70F5CED7B79 FOREIGN KEY (topics_trainings_id) REFERENCES topics_trainings (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trainings ADD CONSTRAINT FK_66DC4330DA6A219 FOREIGN KEY (place_id) REFERENCES places (id)');
        $this->addSql('ALTER TABLE trainings ADD CONSTRAINT FK_66DC43307E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE topics_trainings DROP FOREIGN KEY FK_62955117BF06A414');
        $this->addSql('ALTER TABLE topics_trainings DROP FOREIGN KEY FK_62955117161BA2FF');
        $this->addSql('ALTER TABLE topics_trainings_label_topics_trainings DROP FOREIGN KEY FK_79E70FB4984173');
        $this->addSql('ALTER TABLE topics_trainings_label_topics_trainings DROP FOREIGN KEY FK_79E70F5CED7B79');
        $this->addSql('ALTER TABLE trainings DROP FOREIGN KEY FK_66DC4330DA6A219');
        $this->addSql('ALTER TABLE trainings DROP FOREIGN KEY FK_66DC43307E3C61F9');
        $this->addSql('DROP TABLE places');
        $this->addSql('DROP TABLE topics');
        $this->addSql('DROP TABLE topics_trainings');
        $this->addSql('DROP TABLE topics_trainings_label');
        $this->addSql('DROP TABLE topics_trainings_label_topics_trainings');
        $this->addSql('DROP TABLE trainings');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
