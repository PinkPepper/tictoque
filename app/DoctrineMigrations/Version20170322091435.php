<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170322091435 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, user INT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_6EEAA67D8D93D649 (user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_menu (id INT AUTO_INCREMENT NOT NULL, commande INT DEFAULT NULL, menus INT DEFAULT NULL, INDEX IDX_16693B706EEAA67D (commande), INDEX IDX_16693B70727508CF (menus), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_produit (id INT AUTO_INCREMENT NOT NULL, commande INT DEFAULT NULL, produits INT DEFAULT NULL, quantiteCommandee VARCHAR(255) NOT NULL, INDEX IDX_DF1E9E876EEAA67D (commande), INDEX IDX_DF1E9E87BE2DDF8C (produits), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D8D93D649 FOREIGN KEY (user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande_menu ADD CONSTRAINT FK_16693B706EEAA67D FOREIGN KEY (commande) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_menu ADD CONSTRAINT FK_16693B70727508CF FOREIGN KEY (menus) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E876EEAA67D FOREIGN KEY (commande) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commande_produit ADD CONSTRAINT FK_DF1E9E87BE2DDF8C FOREIGN KEY (produits) REFERENCES produit (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commande_menu DROP FOREIGN KEY FK_16693B706EEAA67D');
        $this->addSql('ALTER TABLE commande_produit DROP FOREIGN KEY FK_DF1E9E876EEAA67D');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commande_menu');
        $this->addSql('DROP TABLE commande_produit');
    }
}
