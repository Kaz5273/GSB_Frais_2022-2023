<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220916094213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_frais DROP FOREIGN KEY FK_5FC0A6A7682A41C');
        $this->addSql('DROP INDEX IDX_5FC0A6A7682A41C ON fiche_frais');
        $this->addSql('ALTER TABLE fiche_frais DROP ligne_frais_hors_forfait_id');
        $this->addSql('ALTER TABLE ligne_frais_hors_forfait ADD fiche_frais_id INT NOT NULL');
        $this->addSql('ALTER TABLE ligne_frais_hors_forfait ADD CONSTRAINT FK_EC01626DD94F5755 FOREIGN KEY (fiche_frais_id) REFERENCES fiche_frais (id)');
        $this->addSql('CREATE INDEX IDX_EC01626DD94F5755 ON ligne_frais_hors_forfait (fiche_frais_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_frais ADD ligne_frais_hors_forfait_id INT NOT NULL');
        $this->addSql('ALTER TABLE fiche_frais ADD CONSTRAINT FK_5FC0A6A7682A41C FOREIGN KEY (ligne_frais_hors_forfait_id) REFERENCES ligne_frais_hors_forfait (id)');
        $this->addSql('CREATE INDEX IDX_5FC0A6A7682A41C ON fiche_frais (ligne_frais_hors_forfait_id)');
        $this->addSql('ALTER TABLE ligne_frais_hors_forfait DROP FOREIGN KEY FK_EC01626DD94F5755');
        $this->addSql('DROP INDEX IDX_EC01626DD94F5755 ON ligne_frais_hors_forfait');
        $this->addSql('ALTER TABLE ligne_frais_hors_forfait DROP fiche_frais_id');
    }
}
