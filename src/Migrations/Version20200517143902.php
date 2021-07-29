<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200517143902 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE aeroport (id INT AUTO_INCREMENT NOT NULL, nom_aeroport VARCHAR(100) NOT NULL, code_aeroport VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avion (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, code_avion VARCHAR(100) NOT NULL, nom_avion VARCHAR(255) NOT NULL, INDEX IDX_234D9D38BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_avion (id INT AUTO_INCREMENT NOT NULL, code_categorie VARCHAR(255) NOT NULL, capacite INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, cin INT NOT NULL, date_de_naissance DATE NOT NULL, nom_employe VARCHAR(100) NOT NULL, type_employe INT NOT NULL, photo VARCHAR(255) DEFAULT NULL, login VARCHAR(255) DEFAULT NULL, mot_de_passe VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervention (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tache (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, duree_max TIME NOT NULL, code_tache VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tve (id INT AUTO_INCREMENT NOT NULL, vol_id INT NOT NULL, employe_id INT NOT NULL, tache_id INT NOT NULL, date_debut_tache DATETIME DEFAULT NULL, date_fin_tache DATETIME DEFAULT NULL, reclamation VARCHAR(255) DEFAULT NULL, INDEX IDX_E80452399F2BFB7A (vol_id), INDEX IDX_E80452391B65292 (employe_id), INDEX IDX_E8045239D2235D39 (tache_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vol (id INT AUTO_INCREMENT NOT NULL, aeroport_arrivee_id INT NOT NULL, aeroport_depart_id INT NOT NULL, avion_id INT NOT NULL, intervention_id INT DEFAULT NULL, num_vol INT NOT NULL, date_arrivee DATETIME NOT NULL, date_depart DATETIME NOT NULL, duree_au_sol TIME NOT NULL, retard TINYINT(1) DEFAULT NULL, avancement INT DEFAULT NULL, retard_prediction TINYINT(1) DEFAULT NULL, INDEX IDX_95C97EBA1B96354 (aeroport_arrivee_id), INDEX IDX_95C97EBE3CBAF6E (aeroport_depart_id), INDEX IDX_95C97EB80BBB841 (avion_id), UNIQUE INDEX UNIQ_95C97EB8EAE3863 (intervention_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vol_aeroport (vol_id INT NOT NULL, aeroport_id INT NOT NULL, INDEX IDX_EF2CE73D9F2BFB7A (vol_id), INDEX IDX_EF2CE73DF1089B86 (aeroport_id), PRIMARY KEY(vol_id, aeroport_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avion ADD CONSTRAINT FK_234D9D38BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_avion (id)');
        $this->addSql('ALTER TABLE tve ADD CONSTRAINT FK_E80452399F2BFB7A FOREIGN KEY (vol_id) REFERENCES vol (id)');
        $this->addSql('ALTER TABLE tve ADD CONSTRAINT FK_E80452391B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE tve ADD CONSTRAINT FK_E8045239D2235D39 FOREIGN KEY (tache_id) REFERENCES tache (id)');
        $this->addSql('ALTER TABLE vol ADD CONSTRAINT FK_95C97EBA1B96354 FOREIGN KEY (aeroport_arrivee_id) REFERENCES aeroport (id)');
        $this->addSql('ALTER TABLE vol ADD CONSTRAINT FK_95C97EBE3CBAF6E FOREIGN KEY (aeroport_depart_id) REFERENCES aeroport (id)');
        $this->addSql('ALTER TABLE vol ADD CONSTRAINT FK_95C97EB80BBB841 FOREIGN KEY (avion_id) REFERENCES avion (id)');
        $this->addSql('ALTER TABLE vol ADD CONSTRAINT FK_95C97EB8EAE3863 FOREIGN KEY (intervention_id) REFERENCES intervention (id)');
        $this->addSql('ALTER TABLE vol_aeroport ADD CONSTRAINT FK_EF2CE73D9F2BFB7A FOREIGN KEY (vol_id) REFERENCES vol (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vol_aeroport ADD CONSTRAINT FK_EF2CE73DF1089B86 FOREIGN KEY (aeroport_id) REFERENCES aeroport (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vol DROP FOREIGN KEY FK_95C97EBA1B96354');
        $this->addSql('ALTER TABLE vol DROP FOREIGN KEY FK_95C97EBE3CBAF6E');
        $this->addSql('ALTER TABLE vol_aeroport DROP FOREIGN KEY FK_EF2CE73DF1089B86');
        $this->addSql('ALTER TABLE vol DROP FOREIGN KEY FK_95C97EB80BBB841');
        $this->addSql('ALTER TABLE avion DROP FOREIGN KEY FK_234D9D38BCF5E72D');
        $this->addSql('ALTER TABLE tve DROP FOREIGN KEY FK_E80452391B65292');
        $this->addSql('ALTER TABLE vol DROP FOREIGN KEY FK_95C97EB8EAE3863');
        $this->addSql('ALTER TABLE tve DROP FOREIGN KEY FK_E8045239D2235D39');
        $this->addSql('ALTER TABLE tve DROP FOREIGN KEY FK_E80452399F2BFB7A');
        $this->addSql('ALTER TABLE vol_aeroport DROP FOREIGN KEY FK_EF2CE73D9F2BFB7A');
        $this->addSql('DROP TABLE aeroport');
        $this->addSql('DROP TABLE avion');
        $this->addSql('DROP TABLE categorie_avion');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE intervention');
        $this->addSql('DROP TABLE tache');
        $this->addSql('DROP TABLE tve');
        $this->addSql('DROP TABLE vol');
        $this->addSql('DROP TABLE vol_aeroport');
    }
}
