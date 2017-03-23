<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170322123138 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC272038A207');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27598377A6');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2779291B96');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC278B97C84D');
        $this->addSql('DROP INDEX IDX_29A5EC27598377A6 ON produit');
        $this->addSql('DROP INDEX IDX_29A5EC272038A207 ON produit');
        $this->addSql('DROP INDEX IDX_29A5EC2779291B96 ON produit');
        $this->addSql('DROP INDEX IDX_29A5EC278B97C84D ON produit');
        $this->addSql('ALTER TABLE produit ADD menu_entree_id INT DEFAULT NULL, ADD menu_plat_id INT DEFAULT NULL, ADD menu_dessert_id INT DEFAULT NULL, ADD menu_boisson_id INT DEFAULT NULL, DROP plat, DROP entree, DROP dessert, DROP boisson');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27B63BBE24 FOREIGN KEY (menu_entree_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC275FE6A8DE FOREIGN KEY (menu_plat_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2755F6E62F FOREIGN KEY (menu_dessert_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2752E6345B FOREIGN KEY (menu_boisson_id) REFERENCES menu (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27B63BBE24 ON produit (menu_entree_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC275FE6A8DE ON produit (menu_plat_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC2755F6E62F ON produit (menu_dessert_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC2752E6345B ON produit (menu_boisson_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27B63BBE24');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC275FE6A8DE');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2755F6E62F');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2752E6345B');
        $this->addSql('DROP INDEX IDX_29A5EC27B63BBE24 ON produit');
        $this->addSql('DROP INDEX IDX_29A5EC275FE6A8DE ON produit');
        $this->addSql('DROP INDEX IDX_29A5EC2755F6E62F ON produit');
        $this->addSql('DROP INDEX IDX_29A5EC2752E6345B ON produit');
        $this->addSql('ALTER TABLE produit ADD plat INT DEFAULT NULL, ADD entree INT DEFAULT NULL, ADD dessert INT DEFAULT NULL, ADD boisson INT DEFAULT NULL, DROP menu_entree_id, DROP menu_plat_id, DROP menu_dessert_id, DROP menu_boisson_id');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC272038A207 FOREIGN KEY (plat) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27598377A6 FOREIGN KEY (entree) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2779291B96 FOREIGN KEY (dessert) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC278B97C84D FOREIGN KEY (boisson) REFERENCES menu (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27598377A6 ON produit (entree)');
        $this->addSql('CREATE INDEX IDX_29A5EC272038A207 ON produit (plat)');
        $this->addSql('CREATE INDEX IDX_29A5EC2779291B96 ON produit (dessert)');
        $this->addSql('CREATE INDEX IDX_29A5EC278B97C84D ON produit (boisson)');
    }
}
