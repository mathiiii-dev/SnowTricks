<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210225161225 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE figure ADD pictures_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE figure ADD CONSTRAINT FK_2F57B37ABC415685 FOREIGN KEY (pictures_id) REFERENCES picture (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2F57B37ABC415685 ON figure (pictures_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE figure DROP FOREIGN KEY FK_2F57B37ABC415685');
        $this->addSql('DROP INDEX UNIQ_2F57B37ABC415685 ON figure');
        $this->addSql('ALTER TABLE figure DROP pictures_id');
    }
}
