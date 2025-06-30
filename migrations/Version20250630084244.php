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
        $this->addSql('CREATE TABLE book_author (book_id INTEGER NOT NULL, author_id INTEGER NOT NULL, PRIMARY KEY(book_id, author_id), CONSTRAINT FK_A11876B537D925CB FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A11876B560BB6FE6 FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A11876B537D925CB ON book_author (book_id)');
        $this->addSql('CREATE INDEX IDX_A11876B560BB6FE6 ON book_author (author_id)');
        $this->addSql('CREATE TABLE book_category (book_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(book_id, category_id), CONSTRAINT FK_E61B069E37D925CB FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E61B069EBCF5E72D FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E61B069E37D925CB ON book_category (book_id)');
        $this->addSql('CREATE INDEX IDX_E61B069EBCF5E72D ON book_category (category_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__notice AS SELECT id, note, commentaire, date FROM notice');
        $this->addSql('DROP TABLE notice');
        $this->addSql('CREATE TABLE notice (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, book_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL, author_id INTEGER DEFAULT NULL, note INTEGER NOT NULL, commentaire CLOB NOT NULL, date DATETIME NOT NULL, CONSTRAINT FK_8F91ABF037D925CB FOREIGN KEY (book_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_8F91ABF0FB88E14F FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_8F91ABF060BB6FE6 FOREIGN KEY (author_id) REFERENCES author (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO notice (id, note, commentaire, date) SELECT id, note, commentaire, date FROM __temp__notice');
        $this->addSql('DROP TABLE __temp__notice');
        $this->addSql('CREATE INDEX IDX_8F91ABF037D925CB ON notice (book_id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF0FB88E14F ON notice (user_id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF060BB6FE6 ON notice (author_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__loan AS SELECT id, date_debut, date_fin, date_retour, statut FROM loan');
        $this->addSql('DROP TABLE loan');
        $this->addSql('CREATE TABLE loan (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, book_id INTEGER DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, date_retour DATETIME NOT NULL, statut VARCHAR(255) NOT NULL, CONSTRAINT FK_364071D7FB88E14F FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_364071D737D925CB FOREIGN KEY (book_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO loan (id, date_debut, date_fin, date_retour, statut) SELECT id, date_debut, date_fin, date_retour, statut FROM __temp__loan');
        $this->addSql('DROP TABLE __temp__loan');
        $this->addSql('CREATE INDEX IDX_364071D7FB88E14F ON loan (user_id)');
        $this->addSql('CREATE INDEX IDX_364071D737D925CB ON loan (book_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__notification AS SELECT id, contenu, date_envoi, lu, type FROM notification');
        $this->addSql('DROP TABLE notification');
        $this->addSql('CREATE TABLE notification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, book_id INTEGER DEFAULT NULL, category_id INTEGER DEFAULT NULL, contenu CLOB NOT NULL, date_envoi DATETIME NOT NULL, lu BOOLEAN NOT NULL, type VARCHAR(255) NOT NULL, CONSTRAINT FK_BF5476CAFB88E14F FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BF5476CA37D925CB FOREIGN KEY (book_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BF5476CABCF5E72D FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO notification (id, contenu, date_envoi, lu, type) SELECT id, contenu, date_envoi, lu, type FROM __temp__notification');
        $this->addSql('DROP TABLE __temp__notification');
        $this->addSql('CREATE INDEX IDX_BF5476CAFB88E14F ON notification (user_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CA37D925CB ON notification (book_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CABCF5E72D ON notification (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE book_author');
        $this->addSql('DROP TABLE book_category');
        $this->addSql('CREATE TEMPORARY TABLE __temp__notice AS SELECT id, note, commentaire, date FROM notice');
        $this->addSql('DROP TABLE notice');
        $this->addSql('CREATE TABLE notice (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, note INTEGER NOT NULL, commentaire CLOB NOT NULL, date DATETIME NOT NULL)');
        $this->addSql('INSERT INTO notice (id, note, commentaire, date) SELECT id, note, commentaire, date FROM __temp__notice');
        $this->addSql('DROP TABLE __temp__notice');
        $this->addSql('CREATE TEMPORARY TABLE __temp__loan AS SELECT id, date_debut, date_fin, date_retour, statut FROM loan');
        $this->addSql('DROP TABLE loan');
        $this->addSql('CREATE TABLE loan (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, date_retour DATETIME NOT NULL, statut VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO loan (id, date_debut, date_fin, date_retour, statut) SELECT id, date_debut, date_fin, date_retour, statut FROM __temp__loan');
        $this->addSql('DROP TABLE __temp__loan');
        $this->addSql('CREATE TEMPORARY TABLE __temp__notification AS SELECT id, contenu, date_envoi, lu, type FROM notification');
        $this->addSql('DROP TABLE notification');
        $this->addSql('CREATE TABLE notification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, contenu CLOB NOT NULL, date_envoi DATETIME NOT NULL, lu BOOLEAN NOT NULL, type VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO notification (id, contenu, date_envoi, lu, type) SELECT id, contenu, date_envoi, lu, type FROM __temp__notification');
        $this->addSql('DROP TABLE __temp__notification');
    }
}
