<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170717115032 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE RelationProduitPointRelais (produit_id INT NOT NULL, pointRelais_id INT NOT NULL, INDEX IDX_795ADE065153081 (pointRelais_id), INDEX IDX_795ADE0F347EFB (produit_id), PRIMARY KEY(pointRelais_id, produit_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
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

        $this->addSql('DROP TABLE RelationProduitPointRelais');
    }
}
