<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210304192150 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, threads VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation_reply (id INT AUTO_INCREMENT NOT NULL, conversation VARCHAR(255) NOT NULL, reply VARCHAR(255) NOT NULL, user VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD thread VARCHAR(255) DEFAULT NULL, ADD title VARCHAR(255) NOT NULL, ADD content VARCHAR(255) NOT NULL, ADD date DATETIME NOT NULL, CHANGE threads user VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE thread ADD category VARCHAR(255) NOT NULL, ADD posts VARCHAR(255) NOT NULL, ADD last_modified_date DATETIME NOT NULL, ADD user VARCHAR(255) NOT NULL, ADD title VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD creation_date VARCHAR(255) NOT NULL, ADD views VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE conversation_reply');
        $this->addSql('ALTER TABLE post ADD threads VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP thread, DROP user, DROP title, DROP content, DROP date');
        $this->addSql('ALTER TABLE thread DROP category, DROP posts, DROP last_modified_date, DROP user, DROP title, DROP description, DROP creation_date, DROP views');
    }
}
