<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170321140535 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE allergene (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_93232AE56C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE RelationProduitAllergene (allergene_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_1E91A9C64646AB2 (allergene_id), INDEX IDX_1E91A9C6F347EFB (produit_id), PRIMARY KEY(allergene_id, produit_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE RelationUserAllergene (allergene_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5C747D604646AB2 (allergene_id), INDEX IDX_5C747D60A76ED395 (user_id), PRIMARY KEY(allergene_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, auteur INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, contenu LONGTEXT NOT NULL, metadescription LONGTEXT NOT NULL, slug VARCHAR(255) NOT NULL, content_changed DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_23A0E66989D9B62 (slug), INDEX IDX_23A0E6655AB140 (auteur), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_497DD6346C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE RelationProduitCategorie (categorie_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_C4CF5517BCF5E72D (categorie_id), INDEX IDX_C4CF5517F347EFB (produit_id), PRIMARY KEY(categorie_id, produit_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, article INT DEFAULT NULL, auteur INT DEFAULT NULL, contenu VARCHAR(255) NOT NULL, content_changed DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_67F068BC23A0E66 (article), INDEX IDX_67F068BC55AB140 (auteur), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, type INT NOT NULL, entree VARCHAR(255) DEFAULT NULL, plat VARCHAR(255) DEFAULT NULL, dessert VARCHAR(255) DEFAULT NULL, boisson VARCHAR(255) DEFAULT NULL, quantite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_7D053A93A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, type VARCHAR(255) DEFAULT NULL, datePeremption DATE NOT NULL, prix INT NOT NULL, quantite INT NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_categorie (produit_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_CDEA88D8F347EFB (produit_id), INDEX IDX_CDEA88D8BCF5E72D (categorie_id), PRIMARY KEY(produit_id, categorie_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_allergene (produit_id INT NOT NULL, allergene_id INT NOT NULL, INDEX IDX_17B47409F347EFB (produit_id), INDEX IDX_17B474094646AB2 (allergene_id), PRIMARY KEY(produit_id, allergene_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, cb VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_allergene (user_id INT NOT NULL, allergene_id INT NOT NULL, INDEX IDX_93C3A701A76ED395 (user_id), INDEX IDX_93C3A7014646AB2 (allergene_id), PRIMARY KEY(user_id, allergene_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE RelationProduitAllergene ADD CONSTRAINT FK_1E91A9C64646AB2 FOREIGN KEY (allergene_id) REFERENCES allergene (id)');
        $this->addSql('ALTER TABLE RelationProduitAllergene ADD CONSTRAINT FK_1E91A9C6F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE RelationUserAllergene ADD CONSTRAINT FK_5C747D604646AB2 FOREIGN KEY (allergene_id) REFERENCES allergene (id)');
        $this->addSql('ALTER TABLE RelationUserAllergene ADD CONSTRAINT FK_5C747D60A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6655AB140 FOREIGN KEY (auteur) REFERENCES user (id)');
        $this->addSql('ALTER TABLE RelationProduitCategorie ADD CONSTRAINT FK_C4CF5517BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE RelationProduitCategorie ADD CONSTRAINT FK_C4CF5517F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC23A0E66 FOREIGN KEY (article) REFERENCES article (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC55AB140 FOREIGN KEY (auteur) REFERENCES user (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE produit_categorie ADD CONSTRAINT FK_CDEA88D8F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_categorie ADD CONSTRAINT FK_CDEA88D8BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_allergene ADD CONSTRAINT FK_17B47409F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_allergene ADD CONSTRAINT FK_17B474094646AB2 FOREIGN KEY (allergene_id) REFERENCES allergene (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_allergene ADD CONSTRAINT FK_93C3A701A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_allergene ADD CONSTRAINT FK_93C3A7014646AB2 FOREIGN KEY (allergene_id) REFERENCES allergene (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE RelationProduitAllergene DROP FOREIGN KEY FK_1E91A9C64646AB2');
        $this->addSql('ALTER TABLE RelationUserAllergene DROP FOREIGN KEY FK_5C747D604646AB2');
        $this->addSql('ALTER TABLE produit_allergene DROP FOREIGN KEY FK_17B474094646AB2');
        $this->addSql('ALTER TABLE user_allergene DROP FOREIGN KEY FK_93C3A7014646AB2');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC23A0E66');
        $this->addSql('ALTER TABLE RelationProduitCategorie DROP FOREIGN KEY FK_C4CF5517BCF5E72D');
        $this->addSql('ALTER TABLE produit_categorie DROP FOREIGN KEY FK_CDEA88D8BCF5E72D');
        $this->addSql('ALTER TABLE RelationProduitAllergene DROP FOREIGN KEY FK_1E91A9C6F347EFB');
        $this->addSql('ALTER TABLE RelationProduitCategorie DROP FOREIGN KEY FK_C4CF5517F347EFB');
        $this->addSql('ALTER TABLE produit_categorie DROP FOREIGN KEY FK_CDEA88D8F347EFB');
        $this->addSql('ALTER TABLE produit_allergene DROP FOREIGN KEY FK_17B47409F347EFB');
        $this->addSql('ALTER TABLE RelationUserAllergene DROP FOREIGN KEY FK_5C747D60A76ED395');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6655AB140');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC55AB140');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93A76ED395');
        $this->addSql('ALTER TABLE user_allergene DROP FOREIGN KEY FK_93C3A701A76ED395');
        $this->addSql('DROP TABLE allergene');
        $this->addSql('DROP TABLE RelationProduitAllergene');
        $this->addSql('DROP TABLE RelationUserAllergene');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE RelationProduitCategorie');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE produit_categorie');
        $this->addSql('DROP TABLE produit_allergene');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_allergene');
    }
}
