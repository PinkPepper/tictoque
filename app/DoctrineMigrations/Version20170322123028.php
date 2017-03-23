<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170322123028 extends AbstractMigration
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
        $this->addSql('DROP INDEX UNIQ_7D053A93598377A6 ON menu');
        $this->addSql('DROP INDEX UNIQ_7D053A932038A207 ON menu');
        $this->addSql('DROP INDEX UNIQ_7D053A9379291B96 ON menu');
        $this->addSql('DROP INDEX UNIQ_7D053A938B97C84D ON menu');
        $this->addSql('ALTER TABLE menu DROP plat, DROP entree, DROP dessert, DROP boisson');
        $this->addSql('ALTER TABLE produit ADD entree INT DEFAULT NULL, ADD plat INT DEFAULT NULL, ADD dessert INT DEFAULT NULL, ADD boisson INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27598377A6 FOREIGN KEY (entree) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC272038A207 FOREIGN KEY (plat) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2779291B96 FOREIGN KEY (dessert) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC278B97C84D FOREIGN KEY (boisson) REFERENCES menu (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27598377A6 ON produit (entree)');
        $this->addSql('CREATE INDEX IDX_29A5EC272038A207 ON produit (plat)');
        $this->addSql('CREATE INDEX IDX_29A5EC2779291B96 ON produit (dessert)');
        $this->addSql('CREATE INDEX IDX_29A5EC278B97C84D ON produit (boisson)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE menu ADD plat INT DEFAULT NULL, ADD entree INT DEFAULT NULL, ADD dessert INT DEFAULT NULL, ADD boisson INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A932038A207 FOREIGN KEY (plat) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93598377A6 FOREIGN KEY (entree) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A9379291B96 FOREIGN KEY (dessert) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A938B97C84D FOREIGN KEY (boisson) REFERENCES produit (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D053A93598377A6 ON menu (entree)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D053A932038A207 ON menu (plat)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D053A9379291B96 ON menu (dessert)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D053A938B97C84D ON menu (boisson)');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27598377A6');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC272038A207');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2779291B96');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC278B97C84D');
        $this->addSql('DROP INDEX IDX_29A5EC27598377A6 ON produit');
        $this->addSql('DROP INDEX IDX_29A5EC272038A207 ON produit');
        $this->addSql('DROP INDEX IDX_29A5EC2779291B96 ON produit');
        $this->addSql('DROP INDEX IDX_29A5EC278B97C84D ON produit');
        $this->addSql('ALTER TABLE produit DROP entree, DROP plat, DROP dessert, DROP boisson');
    }
}
