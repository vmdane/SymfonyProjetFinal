<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250630084244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livre_auteur (livre_id INTEGER NOT NULL, auteur_id INTEGER NOT NULL, PRIMARY KEY(livre_id, auteur_id), CONSTRAINT FK_A11876B537D925CB FOREIGN KEY (livre_id) REFERENCES livre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A11876B560BB6FE6 FOREIGN KEY (auteur_id) REFERENCES auteur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A11876B537D925CB ON livre_auteur (livre_id)');
        $this->addSql('CREATE INDEX IDX_A11876B560BB6FE6 ON livre_auteur (auteur_id)');
        $this->addSql('CREATE TABLE livre_categorie (livre_id INTEGER NOT NULL, categorie_id INTEGER NOT NULL, PRIMARY KEY(livre_id, categorie_id), CONSTRAINT FK_E61B069E37D925CB FOREIGN KEY (livre_id) REFERENCES livre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E61B069EBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E61B069E37D925CB ON livre_categorie (livre_id)');
        $this->addSql('CREATE INDEX IDX_E61B069EBCF5E72D ON livre_categorie (categorie_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__avis AS SELECT id, note, commentaire, date FROM avis');
        $this->addSql('DROP TABLE avis');
        $this->addSql('CREATE TABLE avis (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, livre_id INTEGER DEFAULT NULL, utilisateur_id INTEGER DEFAULT NULL, auteur_id INTEGER DEFAULT NULL, note INTEGER NOT NULL, commentaire CLOB NOT NULL, date DATETIME NOT NULL, CONSTRAINT FK_8F91ABF037D925CB FOREIGN KEY (livre_id) REFERENCES livre (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_8F91ABF0FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_8F91ABF060BB6FE6 FOREIGN KEY (auteur_id) REFERENCES auteur (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO avis (id, note, commentaire, date) SELECT id, note, commentaire, date FROM __temp__avis');
        $this->addSql('DROP TABLE __temp__avis');
        $this->addSql('CREATE INDEX IDX_8F91ABF037D925CB ON avis (livre_id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF0FB88E14F ON avis (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF060BB6FE6 ON avis (auteur_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__emprunt AS SELECT id, date_debut, date_fin, date_retour, statut FROM emprunt');
        $this->addSql('DROP TABLE emprunt');
        $this->addSql('CREATE TABLE emprunt (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER DEFAULT NULL, livre_id INTEGER DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, date_retour DATETIME NOT NULL, statut VARCHAR(255) NOT NULL, CONSTRAINT FK_364071D7FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_364071D737D925CB FOREIGN KEY (livre_id) REFERENCES livre (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO emprunt (id, date_debut, date_fin, date_retour, statut) SELECT id, date_debut, date_fin, date_retour, statut FROM __temp__emprunt');
        $this->addSql('DROP TABLE __temp__emprunt');
        $this->addSql('CREATE INDEX IDX_364071D7FB88E14F ON emprunt (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_364071D737D925CB ON emprunt (livre_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__notification AS SELECT id, contenu, date_envoi, lu, type FROM notification');
        $this->addSql('DROP TABLE notification');
        $this->addSql('CREATE TABLE notification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, utilisateur_id INTEGER DEFAULT NULL, livre_id INTEGER DEFAULT NULL, categorie_id INTEGER DEFAULT NULL, contenu CLOB NOT NULL, date_envoi DATETIME NOT NULL, lu BOOLEAN NOT NULL, type VARCHAR(255) NOT NULL, CONSTRAINT FK_BF5476CAFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BF5476CA37D925CB FOREIGN KEY (livre_id) REFERENCES livre (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BF5476CABCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO notification (id, contenu, date_envoi, lu, type) SELECT id, contenu, date_envoi, lu, type FROM __temp__notification');
        $this->addSql('DROP TABLE __temp__notification');
        $this->addSql('CREATE INDEX IDX_BF5476CAFB88E14F ON notification (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA37D925CB ON notification (livre_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CABCF5E72D ON notification (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE livre_auteur');
        $this->addSql('DROP TABLE livre_categorie');
        $this->addSql('CREATE TEMPORARY TABLE __temp__avis AS SELECT id, note, commentaire, date FROM avis');
        $this->addSql('DROP TABLE avis');
        $this->addSql('CREATE TABLE avis (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, note INTEGER NOT NULL, commentaire CLOB NOT NULL, date DATETIME NOT NULL)');
        $this->addSql('INSERT INTO avis (id, note, commentaire, date) SELECT id, note, commentaire, date FROM __temp__avis');
        $this->addSql('DROP TABLE __temp__avis');
        $this->addSql('CREATE TEMPORARY TABLE __temp__emprunt AS SELECT id, date_debut, date_fin, date_retour, statut FROM emprunt');
        $this->addSql('DROP TABLE emprunt');
        $this->addSql('CREATE TABLE emprunt (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, date_retour DATETIME NOT NULL, statut VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO emprunt (id, date_debut, date_fin, date_retour, statut) SELECT id, date_debut, date_fin, date_retour, statut FROM __temp__emprunt');
        $this->addSql('DROP TABLE __temp__emprunt');
        $this->addSql('CREATE TEMPORARY TABLE __temp__notification AS SELECT id, contenu, date_envoi, lu, type FROM notification');
        $this->addSql('DROP TABLE notification');
        $this->addSql('CREATE TABLE notification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, contenu CLOB NOT NULL, date_envoi DATETIME NOT NULL, lu BOOLEAN NOT NULL, type VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO notification (id, contenu, date_envoi, lu, type) SELECT id, contenu, date_envoi, lu, type FROM __temp__notification');
        $this->addSql('DROP TABLE __temp__notification');
    }
}
