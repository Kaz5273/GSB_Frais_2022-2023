<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220916084939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ligne_frais_hors_forfait (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, montant NUMERIC(10, 2) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fiche_frais ADD ligne_frais_hors_forfait_id INT NOT NULL, DROP mois, CHANGE montant montant NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE fiche_frais ADD CONSTRAINT FK_5FC0A6A7682A41C FOREIGN KEY (ligne_frais_hors_forfait_id) REFERENCES ligne_frais_hors_forfait (id)');
        $this->addSql('CREATE INDEX IDX_5FC0A6A7682A41C ON fiche_frais (ligne_frais_hors_forfait_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_frais DROP FOREIGN KEY FK_5FC0A6A7682A41C');
        $this->addSql('DROP TABLE ligne_frais_hors_forfait');
        $this->addSql('DROP INDEX IDX_5FC0A6A7682A41C ON fiche_frais');
        $this->addSql('ALTER TABLE fiche_frais ADD mois DATE NOT NULL, DROP ligne_frais_hors_forfait_id, CHANGE montant montant NUMERIC(10, 0) NOT NULL');
    }
}
