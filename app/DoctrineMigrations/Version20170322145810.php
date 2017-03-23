<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170322145810 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93734B8089');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93745B52FD');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93AF7BD910');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93D73DB560');
        $this->addSql('DROP INDEX IDX_7D053A93AF7BD910 ON menu');
        $this->addSql('DROP INDEX IDX_7D053A93D73DB560 ON menu');
        $this->addSql('DROP INDEX IDX_7D053A93745B52FD ON menu');
        $this->addSql('DROP INDEX IDX_7D053A93734B8089 ON menu');
        $this->addSql('ALTER TABLE menu ADD entree INT NOT NULL, ADD plat INT NOT NULL, ADD dessert INT NOT NULL, ADD boisson INT NOT NULL, DROP boisson_id, DROP dessert_id, DROP entree_id, DROP plat_id');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93598377A6 FOREIGN KEY (entree) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A932038A207 FOREIGN KEY (plat) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A9379291B96 FOREIGN KEY (dessert) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A938B97C84D FOREIGN KEY (boisson) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_7D053A93598377A6 ON menu (entree)');
        $this->addSql('CREATE INDEX IDX_7D053A932038A207 ON menu (plat)');
        $this->addSql('CREATE INDEX IDX_7D053A9379291B96 ON menu (dessert)');
        $this->addSql('CREATE INDEX IDX_7D053A938B97C84D ON menu (boisson)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93598377A6');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A932038A207');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A9379291B96');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A938B97C84D');
        $this->addSql('DROP INDEX IDX_7D053A93598377A6 ON menu');
        $this->addSql('DROP INDEX IDX_7D053A932038A207 ON menu');
        $this->addSql('DROP INDEX IDX_7D053A9379291B96 ON menu');
        $this->addSql('DROP INDEX IDX_7D053A938B97C84D ON menu');
        $this->addSql('ALTER TABLE menu ADD boisson_id INT DEFAULT NULL, ADD dessert_id INT DEFAULT NULL, ADD entree_id INT DEFAULT NULL, ADD plat_id INT DEFAULT NULL, DROP entree, DROP plat, DROP dessert, DROP boisson');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93734B8089 FOREIGN KEY (boisson_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93745B52FD FOREIGN KEY (dessert_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93AF7BD910 FOREIGN KEY (entree_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93D73DB560 FOREIGN KEY (plat_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_7D053A93AF7BD910 ON menu (entree_id)');
        $this->addSql('CREATE INDEX IDX_7D053A93D73DB560 ON menu (plat_id)');
        $this->addSql('CREATE INDEX IDX_7D053A93745B52FD ON menu (dessert_id)');
        $this->addSql('CREATE INDEX IDX_7D053A93734B8089 ON menu (boisson_id)');
    }
}
