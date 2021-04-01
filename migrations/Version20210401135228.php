<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210401135228 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, boutiques_id INT NOT NULL, auteur VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_D9BEC0C4AEB39D13 (boutiques_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4AEB39D13 FOREIGN KEY (boutiques_id) REFERENCES boutiques (id)');
        $this->addSql('ALTER TABLE boutiques ADD adresse VARCHAR(255) NOT NULL, ADD code_postal INT NOT NULL, ADD website VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit ADD boutiques_id INT NOT NULL, CHANGE categories_id categories_id INT NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27AEB39D13 FOREIGN KEY (boutiques_id) REFERENCES boutiques (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27AEB39D13 ON produit (boutiques_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('ALTER TABLE boutiques DROP adresse, DROP code_postal, DROP website');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27AEB39D13');
        $this->addSql('DROP INDEX IDX_29A5EC27AEB39D13 ON produit');
        $this->addSql('ALTER TABLE produit DROP boutiques_id, CHANGE categories_id categories_id INT DEFAULT NULL');
    }
}
