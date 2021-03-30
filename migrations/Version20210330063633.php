<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210330063633 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE camping (id INT AUTO_INCREMENT NOT NULL, offre_id_id INT DEFAULT NULL, localisation_camping VARCHAR(255) NOT NULL, description_camping VARCHAR(255) NOT NULL, type_camping VARCHAR(255) NOT NULL, image_camping VARCHAR(255) DEFAULT NULL, rating_camping INT DEFAULT NULL, average_rating DOUBLE PRECISION DEFAULT NULL, longitude_camping VARCHAR(255) DEFAULT NULL, latitude_camping VARCHAR(255) DEFAULT NULL, INDEX IDX_81A904E4286E79CC (offre_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, nombre_places_offre INT NOT NULL, date_debut_offre DATE NOT NULL, date_fin_offre DATE NOT NULL, prix_offre INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE camping ADD CONSTRAINT FK_81A904E4286E79CC FOREIGN KEY (offre_id_id) REFERENCES offre (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE camping DROP FOREIGN KEY FK_81A904E4286E79CC');
        $this->addSql('DROP TABLE camping');
        $this->addSql('DROP TABLE offre');
    }
}
