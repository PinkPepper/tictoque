<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170717103252 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE produit_point_relais (produit_id INT NOT NULL, point_relais_id INT NOT NULL, INDEX IDX_60224493F347EFB (produit_id), INDEX IDX_60224493F86FC80E (point_relais_id), PRIMARY KEY(produit_id, point_relais_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_point_relais ADD CONSTRAINT FK_60224493F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_point_relais ADD CONSTRAINT FK_60224493F86FC80E FOREIGN KEY (point_relais_id) REFERENCES point_relais (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27CD8D85A9');
        $this->addSql('DROP INDEX IDX_29A5EC27CD8D85A9 ON produit');
        $this->addSql('ALTER TABLE produit DROP pointRelais');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE produit_point_relais');
        $this->addSql('ALTER TABLE produit ADD pointRelais INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27CD8D85A9 FOREIGN KEY (pointRelais) REFERENCES point_relais (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27CD8D85A9 ON produit (pointRelais)');
    }
}
