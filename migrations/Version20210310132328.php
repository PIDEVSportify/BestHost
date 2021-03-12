<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210310132328 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, id_act VARCHAR(16) NOT NULL, type VARCHAR(16) NOT NULL, description VARCHAR(255) DEFAULT NULL, date_val DATE NOT NULL, categorie VARCHAR(255) NOT NULL, Id_gerant VARCHAR(16) DEFAULT NULL, INDEX IDX_AC74095A75126B3E (Id_gerant), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A75126B3E FOREIGN KEY (Id_gerant) REFERENCES gerant (id_gerant)');
        $this->addSql('DROP TABLE activities');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE activities (id INT AUTO_INCREMENT NOT NULL, id_act VARCHAR(16) NOT NULL COLLATE utf8mb4_unicode_ci, type VARCHAR(16) NOT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, date_val DATE NOT NULL, categorie VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, Id_gerant VARCHAR(16) DEFAULT NULL COLLATE utf8mb4_unicode_ci, FULLTEXT INDEX IDX_B5F1AFE531481D1E8CDE5729F7E3E99D (id_act, type, Id_gerant), INDEX IDX_B5F1AFE575126B3E (Id_gerant), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE activities ADD CONSTRAINT FK_B5F1AFE575126B3E FOREIGN KEY (Id_gerant) REFERENCES gerant (id_gerant)');
        $this->addSql('DROP TABLE activity');
    }
}
