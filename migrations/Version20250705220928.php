<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250705220928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE loan ADD lender_id INT DEFAULT NULL, ADD end_date DATETIME DEFAULT NULL, ADD return_date DATETIME DEFAULT NULL, DROP date_debut, DROP date_fin, CHANGE user_id borrower_id INT DEFAULT NULL, CHANGE date_retour start_date DATETIME DEFAULT NULL, CHANGE statut status VARCHAR(255) NOT NULL');
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
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D0311CE312B');
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D03855D3E3D');
        $this->addSql('DROP INDEX IDX_C5D30D0311CE312B ON loan');
        $this->addSql('DROP INDEX IDX_C5D30D03855D3E3D ON loan');
        $this->addSql('ALTER TABLE loan ADD user_id INT DEFAULT NULL, ADD date_debut DATETIME NOT NULL, ADD date_fin DATETIME NOT NULL, ADD date_retour DATETIME DEFAULT NULL, DROP borrower_id, DROP lender_id, DROP start_date, DROP end_date, DROP return_date, CHANGE status statut VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE notification CHANGE content contenu LONGTEXT NOT NULL, CHANGE send_date date_envoi DATETIME NOT NULL, CHANGE is_read lu TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user ADD is_verified TINYINT(1) NOT NULL, ADD type VARCHAR(255) NOT NULL');
    }
}
