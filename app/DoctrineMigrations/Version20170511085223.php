<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170511085223 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE produit_homepage (id INT AUTO_INCREMENT NOT NULL, produit1_id INT DEFAULT NULL, produit2_id INT DEFAULT NULL, produit3_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_E23BE7FDFC0C3279 (produit1_id), UNIQUE INDEX UNIQ_E23BE7FDEEB99D97 (produit2_id), UNIQUE INDEX UNIQ_E23BE7FD5605FAF2 (produit3_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_homepage ADD CONSTRAINT FK_E23BE7FDFC0C3279 FOREIGN KEY (produit1_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit_homepage ADD CONSTRAINT FK_E23BE7FDEEB99D97 FOREIGN KEY (produit2_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit_homepage ADD CONSTRAINT FK_E23BE7FD5605FAF2 FOREIGN KEY (produit3_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit ADD home1_id INT DEFAULT NULL, ADD home2_id INT DEFAULT NULL, ADD home3_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC272FFD3F34 FOREIGN KEY (home1_id) REFERENCES produit_homepage (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC273D4890DA FOREIGN KEY (home2_id) REFERENCES produit_homepage (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2785F4F7BF FOREIGN KEY (home3_id) REFERENCES produit_homepage (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_29A5EC272FFD3F34 ON produit (home1_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_29A5EC273D4890DA ON produit (home2_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_29A5EC2785F4F7BF ON produit (home3_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC272FFD3F34');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC273D4890DA');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2785F4F7BF');
        $this->addSql('DROP TABLE produit_homepage');
        $this->addSql('DROP INDEX UNIQ_29A5EC272FFD3F34 ON produit');
        $this->addSql('DROP INDEX UNIQ_29A5EC273D4890DA ON produit');
        $this->addSql('DROP INDEX UNIQ_29A5EC2785F4F7BF ON produit');
        $this->addSql('ALTER TABLE produit DROP home1_id, DROP home2_id, DROP home3_id');
    }
}
