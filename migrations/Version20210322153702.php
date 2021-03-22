<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210322153702 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boutiques (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaires ADD boutiques_id INT NOT NULL, ADD date_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4AEB39D13 FOREIGN KEY (boutiques_id) REFERENCES boutiques (id)');
        $this->addSql('CREATE INDEX IDX_D9BEC0C4AEB39D13 ON commentaires (boutiques_id)');
        $this->addSql('ALTER TABLE membre DROP confirm_password');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4AEB39D13');
        $this->addSql('DROP TABLE boutiques');
        $this->addSql('DROP INDEX IDX_D9BEC0C4AEB39D13 ON commentaires');
        $this->addSql('ALTER TABLE commentaires DROP boutiques_id, DROP date_creation');
        $this->addSql('ALTER TABLE membre ADD confirm_password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
