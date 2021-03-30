<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210330091458 extends AbstractMigration
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
        $this->addSql('CREATE TABLE gerant (id_gerant VARCHAR(16) NOT NULL, nom VARCHAR(10) NOT NULL, prenom VARCHAR(10) NOT NULL, date_nais DATE NOT NULL, ad_email VARCHAR(50) NOT NULL, cin VARCHAR(8) NOT NULL, PRIMARY KEY(id_gerant)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maison (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT UNSIGNED AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, thread_id INT UNSIGNED NOT NULL, updated_by_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_B6BD307FF675F31B (author_id), INDEX IDX_B6BD307FE2904019 (thread_id), INDEX IDX_B6BD307F896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_like (id INT UNSIGNED AUTO_INCREMENT NOT NULL, message_id INT UNSIGNED NOT NULL, user_id INT NOT NULL, INDEX IDX_5F6DB6A537A1329 (message_id), INDEX IDX_5F6DB6AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id INT UNSIGNED AUTO_INCREMENT NOT NULL, message_id INT UNSIGNED NOT NULL, reported_by_id INT DEFAULT NULL, treated_by_id INT DEFAULT NULL, reason VARCHAR(255) NOT NULL, treated_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_C42F7784537A1329 (message_id), INDEX IDX_C42F778471CE806 (reported_by_id), INDEX IDX_C42F7784794E2304 (treated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thread (id INT UNSIGNED AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, forum_id INT UNSIGNED NOT NULL, last_message_id INT UNSIGNED DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, is_lock TINYINT(1) NOT NULL, is_pin TINYINT(1) NOT NULL, total_messages SMALLINT NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_31204C83989D9B62 (slug), INDEX IDX_31204C83F675F31B (author_id), INDEX IDX_31204C8329CCBAD0 (forum_id), UNIQUE INDEX UNIQ_31204C83BA0E79C3 (last_message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, google_id VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', cin INT DEFAULT NULL, created_at DATETIME NOT NULL, facebook_id VARCHAR(255) DEFAULT NULL, is_banned TINYINT(1) NOT NULL, last_activity_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
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
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C83F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C8329CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum (id)');
        $this->addSql('ALTER TABLE thread ADD CONSTRAINT FK_31204C83BA0E79C3 FOREIGN KEY (last_message_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE act_like ADD CONSTRAINT FK_64EAA1564B89032C FOREIGN KEY (post_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE act_like ADD CONSTRAINT FK_64EAA156A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A75126B3E FOREIGN KEY (Id_gerant) REFERENCES gerant (id_gerant)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECD12469DE2');
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECD727ACA70');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C8329CCBAD0');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A75126B3E');
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECDBA0E79C3');
        $this->addSql('ALTER TABLE message_like DROP FOREIGN KEY FK_5F6DB6A537A1329');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784537A1329');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C83BA0E79C3');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FE2904019');
        $this->addSql('ALTER TABLE act_like DROP FOREIGN KEY FK_64EAA156A76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF675F31B');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F896DBBDE');
        $this->addSql('ALTER TABLE message_like DROP FOREIGN KEY FK_5F6DB6AA76ED395');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F778471CE806');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784794E2304');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE thread DROP FOREIGN KEY FK_31204C83F675F31B');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE core_option');
        $this->addSql('DROP TABLE forum');
        $this->addSql('DROP TABLE gerant');
        $this->addSql('DROP TABLE maison');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE message_like');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE thread');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('ALTER TABLE act_like DROP FOREIGN KEY FK_64EAA1564B89032C');
    }
}
