<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170821214011 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commande ADD identifiant VARCHAR(255) DEFAULT NULL, CHANGE id id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE RelationProduitPointRelais DROP FOREIGN KEY FK_795ADE065153081');
        $this->addSql('ALTER TABLE RelationProduitPointRelais DROP FOREIGN KEY FK_795ADE0F347EFB');
        $this->addSql('ALTER TABLE RelationProduitPointRelais ADD CONSTRAINT FK_795ADE065153081 FOREIGN KEY (pointRelais_id) REFERENCES point_relais (id)');
        $this->addSql('ALTER TABLE RelationProduitPointRelais ADD CONSTRAINT FK_795ADE0F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE RelationProduitPointRelais DROP FOREIGN KEY FK_795ADE065153081');
        $this->addSql('ALTER TABLE RelationProduitPointRelais DROP FOREIGN KEY FK_795ADE0F347EFB');
        $this->addSql('ALTER TABLE RelationProduitPointRelais ADD CONSTRAINT FK_795ADE065153081 FOREIGN KEY (pointRelais_id) REFERENCES point_relais (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE RelationProduitPointRelais ADD CONSTRAINT FK_795ADE0F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande DROP identifiant, CHANGE id id VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
