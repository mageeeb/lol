<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109150948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE eleve (id INT UNSIGNED AUTO_INCREMENT NOT NULL, matiere_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, age SMALLINT UNSIGNED NOT NULL COMMENT \'L\'\'âge de l\'\'élève\', sexe VARCHAR(1) NOT NULL, classe VARCHAR(25) NOT NULL COMMENT \'Devra être une référence à une entité Classe\', avatar VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_ECA105F7F46CD258 (matiere_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT UNSIGNED AUTO_INCREMENT NOT NULL, eleve_id INT UNSIGNED NOT NULL, matiere_id INT NOT NULL, note DOUBLE PRECISION UNSIGNED NOT NULL, INDEX IDX_CFBDFA14A6CC7B2 (eleve_id), INDEX IDX_CFBDFA14F46CD258 (matiere_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F7F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE eleve DROP FOREIGN KEY FK_ECA105F7F46CD258');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14A6CC7B2');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14F46CD258');
        $this->addSql('DROP TABLE eleve');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
