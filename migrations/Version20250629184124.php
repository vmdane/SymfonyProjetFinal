<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250629184124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('CREATE TABLE auteur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, biographie CLOB NOT NULL)');
        $this->addSql('CREATE TABLE avis (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, note INTEGER NOT NULL, commentaire CLOB NOT NULL, date DATETIME NOT NULL)');
        $this->addSql('CREATE TABLE bd (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('CREATE TABLE categorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description CLOB NOT NULL)');
        $this->addSql('CREATE TABLE donneur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('CREATE TABLE emprunt (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, date_retour DATETIME NOT NULL, statut VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE essai (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('CREATE TABLE lecteur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('CREATE TABLE livre (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, date_publication DATE NOT NULL, isbn VARCHAR(255) NOT NULL, disponible BOOLEAN NOT NULL, image_couverture VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE notification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, contenu CLOB NOT NULL, date_envoi DATETIME NOT NULL, lu BOOLEAN NOT NULL, type VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE roman (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('CREATE TABLE utilisateur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, is_verified BOOLEAN NOT NULL, type VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE auteur');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE bd');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE donneur');
        $this->addSql('DROP TABLE emprunt');
        $this->addSql('DROP TABLE essai');
        $this->addSql('DROP TABLE lecteur');
        $this->addSql('DROP TABLE livre');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE roman');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
