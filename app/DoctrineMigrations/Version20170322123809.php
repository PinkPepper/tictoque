<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170322123809 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE menu ADD entree_id INT DEFAULT NULL, ADD plat_id INT DEFAULT NULL, ADD dessert_id INT DEFAULT NULL, ADD boisson_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93AF7BD910 FOREIGN KEY (entree_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93D73DB560 FOREIGN KEY (plat_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93745B52FD FOREIGN KEY (dessert_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93734B8089 FOREIGN KEY (boisson_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_7D053A93AF7BD910 ON menu (entree_id)');
        $this->addSql('CREATE INDEX IDX_7D053A93D73DB560 ON menu (plat_id)');
        $this->addSql('CREATE INDEX IDX_7D053A93745B52FD ON menu (dessert_id)');
        $this->addSql('CREATE INDEX IDX_7D053A93734B8089 ON menu (boisson_id)');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2752E6345B');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2755F6E62F');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC275FE6A8DE');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27B63BBE24');
        $this->addSql('DROP INDEX IDX_29A5EC27B63BBE24 ON produit');
        $this->addSql('DROP INDEX IDX_29A5EC275FE6A8DE ON produit');
        $this->addSql('DROP INDEX IDX_29A5EC2755F6E62F ON produit');
        $this->addSql('DROP INDEX IDX_29A5EC2752E6345B ON produit');
        $this->addSql('ALTER TABLE produit DROP menu_boisson_id, DROP menu_dessert_id, DROP menu_plat_id, DROP menu_entree_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93AF7BD910');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93D73DB560');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93745B52FD');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93734B8089');
        $this->addSql('DROP INDEX IDX_7D053A93AF7BD910 ON menu');
        $this->addSql('DROP INDEX IDX_7D053A93D73DB560 ON menu');
        $this->addSql('DROP INDEX IDX_7D053A93745B52FD ON menu');
        $this->addSql('DROP INDEX IDX_7D053A93734B8089 ON menu');
        $this->addSql('ALTER TABLE menu DROP entree_id, DROP plat_id, DROP dessert_id, DROP boisson_id');
        $this->addSql('ALTER TABLE produit ADD menu_boisson_id INT DEFAULT NULL, ADD menu_dessert_id INT DEFAULT NULL, ADD menu_plat_id INT DEFAULT NULL, ADD menu_entree_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2752E6345B FOREIGN KEY (menu_boisson_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2755F6E62F FOREIGN KEY (menu_dessert_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC275FE6A8DE FOREIGN KEY (menu_plat_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27B63BBE24 FOREIGN KEY (menu_entree_id) REFERENCES menu (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27B63BBE24 ON produit (menu_entree_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC275FE6A8DE ON produit (menu_plat_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC2755F6E62F ON produit (menu_dessert_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC2752E6345B ON produit (menu_boisson_id)');
    }
}
