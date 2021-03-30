<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210330194157 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT UNSIGNED AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, position SMALLINT NOT NULL, UNIQUE INDEX UNIQ_64C19C1989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE core_option (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_FADA63E95E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum (id INT UNSIGNED AUTO_INCREMENT NOT NULL, category_id INT UNSIGNED DEFAULT NULL, parent_id INT UNSIGNED DEFAULT NULL, last_message_id INT UNSIGNED DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, position SMALLINT NOT NULL, is_lock TINYINT(1) NOT NULL, total_threads SMALLINT NOT NULL, total_messages SMALLINT NOT NULL, UNIQUE INDEX UNIQ_852BBECD989D9B62 (slug), INDEX IDX_852BBECD12469DE2 (category_id), INDEX IDX_852BBECD727ACA70 (parent_id), UNIQUE INDEX UNIQ_852BBECDBA0E79C3 (last_message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT UNSIGNED AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, thread_id INT UNSIGNED NOT NULL, updated_by_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_B6BD307FF675F31B (author_id), INDEX IDX_B6BD307FE2904019 (thread_id), INDEX IDX_B6BD307F896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_like (id INT UNSIGNED AUTO_INCREMENT NOT NULL, message_id INT UNSIGNED NOT NULL, user_id INT NOT NULL, INDEX IDX_5F6DB6A537A1329 (message_id), INDEX IDX_5F6DB6AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id INT UNSIGNED AUTO_INCREMENT NOT NULL, message_id INT UNSIGNED NOT NULL, reported_by_id INT DEFAULT NULL, treated_by_id INT DEFAULT NULL, reason VARCHAR(255) NOT NULL, treated_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_C42F7784537A1329 (message_id), INDEX IDX_C42F778471CE806 (reported_by_id), INDEX IDX_C42F7784794E2304 (treated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thread (id INT UNSIGNED AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, forum_id INT UNSIGNED NOT NULL, last_message_id INT UNSIGNED DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, is_lock TINYINT(1) NOT NULL, is_pin TINYINT(1) NOT NULL, total_messages SMALLINT NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_31204C83989D9B62 (slug), INDEX IDX_31204C83F675F31B (author_id), INDEX IDX_31204C8329CCBAD0 (forum_id), UNIQUE INDEX UNIQ_31204C83BA0E79C3 (last_message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECD727ACA70 FOREIGN KEY (parent_id) REFERENCES forum (id)');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECDBA0E79C3 FOREIGN KEY (last_message_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FE2904019 FOREIGN KEY (thread_id) REFERENCES thread (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F896DBBDE FOREIGN KEY (updated_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message_like ADD CONSTRAINT FK_5F6DB6A537A1329 FOREIGN KEY (message_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE message_like ADD CONSTRAINT FK_5F6DB6AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784537A1329 FOREIGN KEY (message_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F778471CE806 FOREIGN KEY (reported_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784794E2304 FOREIGN KEY (treated_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C83F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C8329CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum (id)');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C83BA0E79C3 FOREIGN KEY (last_message_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE user ADD last_activity_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECD12469DE2');
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECD727ACA70');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C8329CCBAD0');
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECDBA0E79C3');
        $this->addSql('ALTER TABLE message_like DROP FOREIGN KEY FK_5F6DB6A537A1329');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784537A1329');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C83BA0E79C3');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FE2904019');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE core_option');
        $this->addSql('DROP TABLE forum');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE message_like');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE thread');
        $this->addSql('ALTER TABLE `user` DROP last_activity_at');
    }
}
