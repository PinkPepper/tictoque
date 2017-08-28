<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170616100241 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE point_relais ADD user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE point_relais ADD CONSTRAINT FK_8A891BEE8D93D649 FOREIGN KEY (user) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8A891BEE8D93D649 ON point_relais (user)');
        $this->addSql('ALTER TABLE produit ADD pointRelais INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27CD8D85A9 FOREIGN KEY (pointRelais) REFERENCES point_relais (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27CD8D85A9 ON produit (pointRelais)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE point_relais DROP FOREIGN KEY FK_8A891BEE8D93D649');
        $this->addSql('DROP INDEX IDX_8A891BEE8D93D649 ON point_relais');
        $this->addSql('ALTER TABLE point_relais DROP user');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27CD8D85A9');
        $this->addSql('DROP INDEX IDX_29A5EC27CD8D85A9 ON produit');
        $this->addSql('ALTER TABLE produit DROP pointRelais');
    }
}
