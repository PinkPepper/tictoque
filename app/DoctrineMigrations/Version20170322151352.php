<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170322151352 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A932038A207');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93598377A6');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A9379291B96');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A938B97C84D');
        $this->addSql('DROP INDEX IDX_7D053A93598377A6 ON menu');
        $this->addSql('DROP INDEX IDX_7D053A932038A207 ON menu');
        $this->addSql('DROP INDEX IDX_7D053A9379291B96 ON menu');
        $this->addSql('DROP INDEX IDX_7D053A938B97C84D ON menu');
        $this->addSql('ALTER TABLE menu ADD id_entree INT NOT NULL, ADD id_plat INT NOT NULL, ADD id_dessert INT NOT NULL, ADD id_boisson INT NOT NULL, DROP plat, DROP entree, DROP dessert, DROP boisson');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE menu ADD plat INT NOT NULL, ADD entree INT NOT NULL, ADD dessert INT NOT NULL, ADD boisson INT NOT NULL, DROP id_entree, DROP id_plat, DROP id_dessert, DROP id_boisson');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A932038A207 FOREIGN KEY (plat) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93598377A6 FOREIGN KEY (entree) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A9379291B96 FOREIGN KEY (dessert) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A938B97C84D FOREIGN KEY (boisson) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_7D053A93598377A6 ON menu (entree)');
        $this->addSql('CREATE INDEX IDX_7D053A932038A207 ON menu (plat)');
        $this->addSql('CREATE INDEX IDX_7D053A9379291B96 ON menu (dessert)');
        $this->addSql('CREATE INDEX IDX_7D053A938B97C84D ON menu (boisson)');
    }
}
