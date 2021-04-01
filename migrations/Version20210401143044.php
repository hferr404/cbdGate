<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210401143044 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boutiques (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, code_postal INT NOT NULL, website VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment_produit (id INT AUTO_INCREMENT NOT NULL, produits_id INT DEFAULT NULL, auteur VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, date_creation DATE NOT NULL, INDEX IDX_DAD68E37CD11A2CF (produits_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, boutiques_id INT NOT NULL, auteur VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_D9BEC0C4AEB39D13 (boutiques_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, produits_id INT DEFAULT NULL, boutiques_id INT DEFAULT NULL, auteur VARCHAR(255) NOT NULL, contenu VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, INDEX IDX_5F9E962ACD11A2CF (produits_id), INDEX IDX_5F9E962AAEB39D13 (boutiques_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membre (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, categories_id INT NOT NULL, boutiques_id INT NOT NULL, titre VARCHAR(255) NOT NULL, contenu VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, prix VARCHAR(255) NOT NULL, INDEX IDX_29A5EC27A21214B7 (categories_id), INDEX IDX_29A5EC27AEB39D13 (boutiques_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_produit ADD CONSTRAINT FK_DAD68E37CD11A2CF FOREIGN KEY (produits_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4AEB39D13 FOREIGN KEY (boutiques_id) REFERENCES boutiques (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962ACD11A2CF FOREIGN KEY (produits_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AAEB39D13 FOREIGN KEY (boutiques_id) REFERENCES boutiques (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27A21214B7 FOREIGN KEY (categories_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27AEB39D13 FOREIGN KEY (boutiques_id) REFERENCES boutiques (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4AEB39D13');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AAEB39D13');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27AEB39D13');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27A21214B7');
        $this->addSql('ALTER TABLE comment_produit DROP FOREIGN KEY FK_DAD68E37CD11A2CF');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962ACD11A2CF');
        $this->addSql('DROP TABLE boutiques');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE comment_produit');
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE membre');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE produits');
    }
}
