<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250705220251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, book_id INT DEFAULT NULL, user_id INT DEFAULT NULL, author_id INT DEFAULT NULL, rate INT NOT NULL, review LONGTEXT NOT NULL, date DATETIME NOT NULL, INDEX IDX_794381C616A2B381 (book_id), INDEX IDX_794381C6A76ED395 (user_id), INDEX IDX_794381C6F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_favorite (user_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_88486AD9A76ED395 (user_id), INDEX IDX_88486AD916A2B381 (book_id), PRIMARY KEY(user_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C616A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6F675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        $this->addSql('ALTER TABLE user_favorite ADD CONSTRAINT FK_88486AD9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_favorite ADD CONSTRAINT FK_88486AD916A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notice DROP FOREIGN KEY FK_480D45C216A2B381');
        $this->addSql('ALTER TABLE notice DROP FOREIGN KEY FK_480D45C2A76ED395');
        $this->addSql('ALTER TABLE notice DROP FOREIGN KEY FK_480D45C2F675F31B');
        $this->addSql('ALTER TABLE user_book DROP FOREIGN KEY FK_B164EFF816A2B381');
        $this->addSql('ALTER TABLE user_book DROP FOREIGN KEY FK_B164EFF8A76ED395');
        $this->addSql('ALTER TABLE user_favoris DROP FOREIGN KEY FK_D13EDA3816A2B381');
        $this->addSql('ALTER TABLE user_favoris DROP FOREIGN KEY FK_D13EDA38A76ED395');
        $this->addSql('DROP TABLE notice');
        $this->addSql('DROP TABLE drive');
        $this->addSql('DROP TABLE essay');
        $this->addSql('DROP TABLE giver');
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('DROP TABLE novel');
        $this->addSql('DROP TABLE bd');
        $this->addSql('DROP TABLE user_book');
        $this->addSql('DROP TABLE user_favoris');
        $this->addSql('ALTER TABLE author DROP name, DROP firstname, CHANGE date_naissance birthdate DATE NOT NULL, CHANGE biographie biography LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE book ADD title VARCHAR(255) NOT NULL, ADD cover_image VARCHAR(255) NOT NULL, DROP titre, DROP image_couverture, DROP type, CHANGE date_publication publication_date DATE NOT NULL, CHANGE disponible available TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D03A76ED395');
        $this->addSql('DROP INDEX IDX_C5D30D03A76ED395 ON loan');
        $this->addSql('ALTER TABLE loan ADD lender_id INT DEFAULT NULL, ADD start_date DATETIME NOT NULL, ADD end_date DATETIME NOT NULL, DROP date_debut, DROP date_fin, CHANGE user_id borrower_id INT DEFAULT NULL, CHANGE date_retour return_date DATETIME DEFAULT NULL, CHANGE statut status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D0311CE312B FOREIGN KEY (borrower_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03855D3E3D FOREIGN KEY (lender_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C5D30D0311CE312B ON loan (borrower_id)');
        $this->addSql('CREATE INDEX IDX_C5D30D03855D3E3D ON loan (lender_id)');
        $this->addSql('ALTER TABLE notification CHANGE contenu content LONGTEXT NOT NULL, CHANGE date_envoi send_date DATETIME NOT NULL, CHANGE lu is_read TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user DROP is_verified, DROP type');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notice (id INT AUTO_INCREMENT NOT NULL, book_id INT DEFAULT NULL, user_id INT DEFAULT NULL, author_id INT DEFAULT NULL, note INT NOT NULL, commentaire LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date DATETIME NOT NULL, INDEX IDX_480D45C216A2B381 (book_id), INDEX IDX_480D45C2A76ED395 (user_id), INDEX IDX_480D45C2F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE drive (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE essay (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE giver (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE `admin` (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE novel (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bd (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_book (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, book_id INT NOT NULL, added_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_for_loan TINYINT(1) NOT NULL, INDEX IDX_B164EFF816A2B381 (book_id), INDEX IDX_B164EFF8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_favoris (user_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_D13EDA3816A2B381 (book_id), INDEX IDX_D13EDA38A76ED395 (user_id), PRIMARY KEY(user_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C216A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C2F675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user_book ADD CONSTRAINT FK_B164EFF816A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user_book ADD CONSTRAINT FK_B164EFF8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user_favoris ADD CONSTRAINT FK_D13EDA3816A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_favoris ADD CONSTRAINT FK_D13EDA38A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C616A2B381');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6F675F31B');
        $this->addSql('ALTER TABLE user_favorite DROP FOREIGN KEY FK_88486AD9A76ED395');
        $this->addSql('ALTER TABLE user_favorite DROP FOREIGN KEY FK_88486AD916A2B381');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE user_favorite');
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D0311CE312B');
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D03855D3E3D');
        $this->addSql('DROP INDEX IDX_C5D30D0311CE312B ON loan');
        $this->addSql('DROP INDEX IDX_C5D30D03855D3E3D ON loan');
        $this->addSql('ALTER TABLE loan ADD user_id INT DEFAULT NULL, ADD date_debut DATETIME NOT NULL, ADD date_fin DATETIME NOT NULL, DROP borrower_id, DROP lender_id, DROP start_date, DROP end_date, CHANGE return_date date_retour DATETIME DEFAULT NULL, CHANGE status statut VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C5D30D03A76ED395 ON loan (user_id)');
        $this->addSql('ALTER TABLE book ADD titre VARCHAR(255) NOT NULL, ADD image_couverture VARCHAR(255) NOT NULL, ADD type VARCHAR(255) NOT NULL, DROP title, DROP cover_image, CHANGE publication_date date_publication DATE NOT NULL, CHANGE available disponible TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE notification CHANGE content contenu LONGTEXT NOT NULL, CHANGE send_date date_envoi DATETIME NOT NULL, CHANGE is_read lu TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user ADD is_verified TINYINT(1) NOT NULL, ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE author ADD name VARCHAR(255) NOT NULL, ADD firstname VARCHAR(255) NOT NULL, CHANGE birthdate date_naissance DATE NOT NULL, CHANGE biography biographie LONGTEXT NOT NULL');
    }
}
